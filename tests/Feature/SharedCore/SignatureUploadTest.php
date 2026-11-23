<?php

namespace Tests\Feature\SharedCore;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Modules\SharedCore\Models\Role;
use Modules\SharedCore\Models\SignatureImage;
use Modules\SharedCore\Services\SignatureService;
use Tests\TestCase;

class SignatureUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        Storage::fake('local');
        Storage::fake('public');
    }

    private function createUserWithRole(string $roleSlug, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $role = Role::where('slug', $roleSlug)->firstOrFail();

        $user->roles()->attach($role->id, [
            'period_id'   => null,
            'assigned_by' => $user->id,
            'assigned_at' => now(),
        ]);

        return $user;
    }

    private function createFakeJpegBytes(): string
    {
        return (string) base64_decode(
            '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA='
        );
    }

    public function test_ketua_and_sekretaris_can_access_signature_page(): void
    {
        $ketua = $this->createUserWithRole('ketua', [
            'name' => 'Ahmad Ketua',
            'npa'  => 'KTU-101',
        ]);
        $sekretaris = $this->createUserWithRole('sekretaris', [
            'name' => 'Budi Sekretaris',
            'npa'  => 'SKR-102',
        ]);

        $this->actingAs($ketua)
            ->get(route('shared.signature.edit'))
            ->assertOk()
            ->assertViewIs('shared-core::signature.edit')
            ->assertViewHas('signature', null)
            ->assertViewHas('signatureUrl', null)
            ->assertViewHas('historyCount', 0)
            ->assertViewHas('userRoleName', 'Ketua')
            ->assertSee('Ahmad Ketua')
            ->assertSee('KTU-101');

        $this->actingAs($sekretaris)
            ->get(route('shared.signature.edit'))
            ->assertOk()
            ->assertViewIs('shared-core::signature.edit')
            ->assertViewHas('userRoleName', 'Sekretaris')
            ->assertSee('Budi Sekretaris')
            ->assertSee('SKR-102');
    }

    public function test_ketua_can_upload_valid_png_and_preview_with_secure_headers(): void
    {
        $ketua = $this->createUserWithRole('ketua');
        $png = UploadedFile::fake()->image('ttd-ketua.png', 240, 100);

        $uploadResponse = $this->actingAs($ketua)
            ->post(route('shared.signature.store'), [
                'signature' => $png,
            ]);

        $uploadResponse
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $signature = $ketua->activeSignature()->first();
        $this->assertNotNull($signature);
        $this->assertTrue($signature->is_active);
        Storage::disk('local')->assertExists($signature->file_path);
        $this->assertSame([], Storage::disk('public')->allFiles());

        $previewResponse = $this->actingAs($ketua)
            ->get(route('shared.signature.preview'));

        $previewResponse
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $cacheControl = strtolower((string) $previewResponse->headers->get('Cache-Control'));
        $this->assertStringContainsString('private', $cacheControl);
        $this->assertStringContainsString('no-store', $cacheControl);
    }

    public function test_replacing_signature_updates_active_record_and_increments_history_count(): void
    {
        $sekretaris = $this->createUserWithRole('sekretaris');

        $this->actingAs($sekretaris)
            ->post(route('shared.signature.store'), [
                'signature' => UploadedFile::fake()->image('ttd-v1.png', 180, 70),
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHas('success');

        $first = SignatureImage::where('user_id', $sekretaris->id)->firstOrFail();

        $this->actingAs($sekretaris)
            ->post(route('shared.signature.store'), [
                'signature' => UploadedFile::fake()->image('ttd-v2.png', 260, 110),
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHas('success');

        $first->refresh();
        $active = $sekretaris->activeSignature()->firstOrFail();

        $this->assertFalse($first->is_active);
        $this->assertTrue($active->is_active);
        $this->assertNotSame($first->id, $active->id);
        Storage::disk('local')->assertExists($first->file_path);
        Storage::disk('local')->assertExists($active->file_path);

        $this->actingAs($sekretaris)
            ->get(route('shared.signature.edit'))
            ->assertOk()
            ->assertViewHas('historyCount', 2)
            ->assertViewHas('signature', fn (?SignatureImage $sig) => $sig?->id === $active->id);
    }

    public function test_delete_deactivates_active_signature_and_subsequent_preview_returns_404(): void
    {
        $ketua = $this->createUserWithRole('ketua');

        $this->actingAs($ketua)
            ->post(route('shared.signature.store'), [
                'signature' => UploadedFile::fake()->image('ttd-aktif.png', 200, 90),
            ])
            ->assertRedirect(route('shared.signature.edit'));

        $signature = $ketua->activeSignature()->firstOrFail();

        $this->actingAs($ketua)
            ->delete(route('shared.signature.destroy'))
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHas('success');

        $this->assertFalse($signature->fresh()->is_active);
        Storage::disk('local')->assertExists($signature->file_path);

        $this->actingAs($ketua)
            ->get(route('shared.signature.preview'))
            ->assertNotFound();
    }

    public function test_upload_rejects_missing_file(): void
    {
        $ketua = $this->createUserWithRole('ketua');

        $this->actingAs($ketua)
            ->from(route('shared.signature.edit'))
            ->post(route('shared.signature.store'), [])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHasErrors('signature');

        $this->assertDatabaseCount('signature_images', 0);
    }

    public function test_upload_rejects_jpg_and_pdf_files(): void
    {
        $ketua = $this->createUserWithRole('ketua');

        $this->actingAs($ketua)
            ->from(route('shared.signature.edit'))
            ->post(route('shared.signature.store'), [
                'signature' => UploadedFile::fake()->createWithContent('ttd.jpg', $this->createFakeJpegBytes()),
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHasErrors('signature');

        $this->actingAs($ketua)
            ->from(route('shared.signature.edit'))
            ->post(route('shared.signature.store'), [
                'signature' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHasErrors('signature');

        $this->assertDatabaseCount('signature_images', 0);
    }

    public function test_upload_rejects_text_php_and_jpg_files_renamed_to_png(): void
    {
        $ketua = $this->createUserWithRole('ketua');

        $fakeTextPng = UploadedFile::fake()->createWithContent('teks.png', 'ini bukan file gambar png');
        $fakePhpPng = UploadedFile::fake()->createWithContent('shell.png', '<?php system("whoami"); ?>');
        $fakeJpgAsPng = UploadedFile::fake()->createWithContent('foto.png', $this->createFakeJpegBytes());
        $fakeHeaderOnlyPng = UploadedFile::fake()->createWithContent('polyglot.png', "\x89PNG\r\n\x1a\n<?php echo 1; ?>");

        foreach ([$fakeTextPng, $fakePhpPng, $fakeJpgAsPng, $fakeHeaderOnlyPng] as $maliciousFile) {
            $this->actingAs($ketua)
                ->from(route('shared.signature.edit'))
                ->post(route('shared.signature.store'), [
                    'signature' => $maliciousFile,
                ])
                ->assertRedirect(route('shared.signature.edit'))
                ->assertSessionHasErrors('signature');
        }

        $this->assertDatabaseCount('signature_images', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_upload_rejects_file_larger_than_2mb(): void
    {
        $ketua = $this->createUserWithRole('ketua');
        $oversizedPng = UploadedFile::fake()->image('besar.png', 200, 80)->size(2049);

        $this->actingAs($ketua)
            ->from(route('shared.signature.edit'))
            ->post(route('shared.signature.store'), [
                'signature' => $oversizedPng,
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHasErrors('signature');

        $this->assertDatabaseCount('signature_images', 0);
    }

    public function test_upload_rejects_dimensions_too_small_or_too_large(): void
    {
        $ketua = $this->createUserWithRole('ketua');

        $tooSmall = UploadedFile::fake()->image('kecil.png', 79, 39);
        $tooLarge = UploadedFile::fake()->image('raksasa.png', 3001, 200);

        $this->actingAs($ketua)
            ->from(route('shared.signature.edit'))
            ->post(route('shared.signature.store'), [
                'signature' => $tooSmall,
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHasErrors('signature');

        $this->actingAs($ketua)
            ->from(route('shared.signature.edit'))
            ->post(route('shared.signature.store'), [
                'signature' => $tooLarge,
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHasErrors('signature');

        $this->assertDatabaseCount('signature_images', 0);
    }

    public function test_delete_without_active_signature_returns_friendly_error_flash(): void
    {
        $ketua = $this->createUserWithRole('ketua');

        $this->actingAs($ketua)
            ->delete(route('shared.signature.destroy'))
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHas('error');
    }

    public function test_preview_returns_404_when_no_active_signature_or_physical_file_is_missing(): void
    {
        $ketua = $this->createUserWithRole('ketua');

        $this->actingAs($ketua)
            ->get(route('shared.signature.preview'))
            ->assertNotFound();

        $signature = app(SignatureService::class)->uploadForUser(
            $ketua,
            UploadedFile::fake()->image('ttd-hilang.png', 200, 80)
        );

        Storage::disk('local')->delete($signature->file_path);

        $this->actingAs($ketua)
            ->get(route('shared.signature.preview'))
            ->assertNotFound();

        $this->actingAs($ketua)
            ->get(route('shared.signature.edit'))
            ->assertOk()
            ->assertViewHas('signatureUrl', null)
            ->assertSee('Berkas tanda tangan aktif tidak ditemukan di penyimpanan.');
    }

    public function test_guest_is_redirected_to_login_on_all_signature_endpoints(): void
    {
        $this->get(route('shared.signature.edit'))->assertRedirect(route('login'));
        $this->post(route('shared.signature.store'))->assertRedirect(route('login'));
        $this->get(route('shared.signature.preview'))->assertRedirect(route('login'));
        $this->delete(route('shared.signature.destroy'))->assertRedirect(route('login'));
    }

    public function test_roles_without_signature_permission_receive_403_on_all_signature_endpoints(): void
    {
        foreach (['superadmin', 'bidang_administrasi', 'anggota'] as $roleSlug) {
            $user = $this->createUserWithRole($roleSlug);

            $this->actingAs($user)
                ->get(route('shared.signature.edit'))
                ->assertForbidden();

            $this->actingAs($user)
                ->post(route('shared.signature.store'), [
                    'signature' => UploadedFile::fake()->image('ttd.png', 200, 80),
                ])
                ->assertForbidden();

            $this->actingAs($user)
                ->get(route('shared.signature.preview'))
                ->assertForbidden();

            $this->actingAs($user)
                ->delete(route('shared.signature.destroy'))
                ->assertForbidden();
        }

        $this->assertDatabaseCount('signature_images', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_signature_image_policy_denies_other_users_and_superadmin(): void
    {
        $owner = $this->createUserWithRole('ketua');
        $otherUser = $this->createUserWithRole('sekretaris');
        $superadmin = $this->createUserWithRole('superadmin');

        $signature = app(SignatureService::class)->uploadForUser(
            $owner,
            UploadedFile::fake()->image('ttd-owner.png', 200, 80)
        );

        $this->assertTrue(Gate::forUser($owner)->allows('view', $signature));
        $this->assertTrue(Gate::forUser($owner)->allows('delete', $signature));

        $this->assertFalse(Gate::forUser($otherUser)->allows('view', $signature));
        $this->assertFalse(Gate::forUser($otherUser)->allows('delete', $signature));

        $this->assertFalse(Gate::forUser($superadmin)->allows('view', $signature));
        $this->assertFalse(Gate::forUser($superadmin)->allows('delete', $signature));

        $this->actingAs($otherUser)
            ->get(route('shared.signature.preview'))
            ->assertNotFound();

        $this->actingAs($superadmin)
            ->get(route('shared.signature.preview'))
            ->assertForbidden();

        $this->actingAs($superadmin)
            ->delete(route('shared.signature.destroy'))
            ->assertForbidden();

        $this->assertTrue($signature->fresh()->is_active);
    }

    public function test_uploaded_signature_is_never_stored_on_public_disk(): void
    {
        $sekretaris = $this->createUserWithRole('sekretaris');

        $this->actingAs($sekretaris)
            ->post(route('shared.signature.store'), [
                'signature' => UploadedFile::fake()->image('ttd-privat.png', 240, 90),
            ])
            ->assertRedirect(route('shared.signature.edit'))
            ->assertSessionHas('success');

        $signature = $sekretaris->activeSignature()->firstOrFail();

        Storage::disk('local')->assertExists($signature->file_path);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }
}
