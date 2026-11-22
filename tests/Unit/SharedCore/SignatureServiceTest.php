<?php

namespace Tests\Unit\SharedCore;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\SharedCore\Models\SignatureImage;
use Modules\SharedCore\Services\SignatureService;
use RuntimeException;
use Tests\TestCase;

class SignatureServiceTest extends TestCase
{
    use RefreshDatabase;

    private SignatureService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

        $this->service = app(SignatureService::class);
    }

    public function test_upload_for_user_stores_uuid_png_on_local_disk_and_returns_active_model(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('ttd-asli-ketua.png', 240, 100);

        $signature = $this->service->uploadForUser($user, $file);

        $this->assertInstanceOf(SignatureImage::class, $signature);
        $this->assertSame($user->id, $signature->user_id);
        $this->assertTrue($signature->is_active);
        $this->assertNotNull($signature->uploaded_at);
        $this->assertMatchesRegularExpression(
            '/^signatures\/'.$user->id.'\/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.png$/',
            $signature->file_path
        );
        $this->assertStringNotContainsString('ttd-asli-ketua', $signature->file_path);

        Storage::disk('local')->assertExists($signature->file_path);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_second_upload_deactivates_previous_record_without_deleting_old_record_or_file(): void
    {
        $user = User::factory()->create();

        $first = $this->service->uploadForUser(
            $user,
            UploadedFile::fake()->image('ttd-lama.png', 200, 80)
        );
        $second = $this->service->uploadForUser(
            $user,
            UploadedFile::fake()->image('ttd-baru.png', 260, 100)
        );

        $first->refresh();
        $second->refresh();

        $this->assertFalse($first->is_active);
        $this->assertTrue($second->is_active);
        $this->assertNotSame($first->file_path, $second->file_path);

        Storage::disk('local')->assertExists($first->file_path);
        Storage::disk('local')->assertExists($second->file_path);

        $this->assertSame(2, $user->signatureImages()->count());
        $this->assertSame($second->id, $this->service->getActiveForUser($user)?->id);
    }

    public function test_upload_deletes_orphan_file_when_database_transaction_fails(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('ttd-gagal.png', 200, 80);

        SignatureImage::creating(function (): void {
            throw new RuntimeException('Simulasi gagal simpan ke database.');
        });

        try {
            $this->expectException(RuntimeException::class);
            $this->service->uploadForUser($user, $file);
        } finally {
            SignatureImage::flushEventListeners();
        }

        $this->assertDatabaseCount('signature_images', 0);
        $this->assertSame([], Storage::disk('local')->allFiles("signatures/{$user->id}"));
    }

    public function test_remove_active_for_user_soft_deactivates_record_and_returns_boolean(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($this->service->removeActiveForUser($user));

        $signature = $this->service->uploadForUser(
            $user,
            UploadedFile::fake()->image('ttd.png', 200, 80)
        );

        $this->assertTrue($this->service->removeActiveForUser($user));
        $this->assertFalse($signature->fresh()->is_active);
        Storage::disk('local')->assertExists($signature->file_path);
        $this->assertNull($this->service->getActiveForUser($user));

        $this->assertFalse($this->service->removeActiveForUser($user));
    }

    public function test_get_absolute_path_and_get_data_uri_handle_existing_and_missing_files(): void
    {
        $user = User::factory()->create();
        $signature = $this->service->uploadForUser(
            $user,
            UploadedFile::fake()->image('ttd-pdf.png', 240, 100)
        );

        $absolutePath = $this->service->getAbsolutePath($signature);
        $dataUri = $this->service->getDataUri($signature);

        $this->assertSame(Storage::disk('local')->path($signature->file_path), $absolutePath);
        $this->assertFileExists($absolutePath);

        $this->assertNotNull($dataUri);
        $this->assertStringStartsWith('data:image/png;base64,', $dataUri);
        $decoded = base64_decode(substr($dataUri, strlen('data:image/png;base64,')), true);
        $this->assertNotFalse($decoded);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $decoded);

        Storage::disk('local')->delete($signature->file_path);

        $this->assertNull($this->service->getAbsolutePath($signature));
        $this->assertNull($this->service->getDataUri($signature));
    }

    public function test_user_has_active_signature_and_active_signature_relation(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->hasActiveSignature());
        $this->assertNull($user->activeSignature);

        $first = $this->service->uploadForUser(
            $user,
            UploadedFile::fake()->image('ttd-1.png', 200, 80)
        );

        $this->assertTrue($user->hasActiveSignature());
        $this->assertSame($first->id, $user->activeSignature?->id);

        $second = $this->service->uploadForUser(
            $user,
            UploadedFile::fake()->image('ttd-2.png', 220, 90)
        );

        $this->assertTrue($user->hasActiveSignature());
        $this->assertSame($second->id, $user->activeSignature?->id);

        $this->service->removeActiveForUser($user);

        $this->assertFalse($user->hasActiveSignature());
        $this->assertNull($user->activeSignature);
    }
}
