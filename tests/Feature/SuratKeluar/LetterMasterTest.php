<?php

namespace Tests\Feature\SuratKeluar;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Modules\SuratKeluar\Models\LetterDestinationCode;
use Modules\SuratKeluar\Models\LetterTemplate;
use Modules\SuratKeluar\Models\LetterType;
use Tests\TestCase;

class LetterMasterTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithRole(string $slug, string $name): User
    {
        $roleId = DB::table('roles')->insertGetId([
            'name' => $name,
            'slug' => $slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::factory()->create();
        $user->roles()->attach($roleId, [
            'assigned_by' => $user->id,
            'assigned_at' => now(),
        ]);

        return $user;
    }

    public function test_bidang_administrasi_dan_super_admin_dapat_mengakses_master_surat(): void
    {
        $admin = $this->createUserWithRole('bidang-administrasi', 'Bidang Administrasi');
        $superAdmin = $this->createUserWithRole('super_admin', 'Super Admin');

        $this->actingAs($admin)
            ->get(route('surat-keluar.master.letter-types.index'))
            ->assertOk();

        $this->actingAs($superAdmin)
            ->get(route('surat-keluar.master.destination-codes.index'))
            ->assertOk();

        $this->actingAs($superAdmin)
            ->get(route('surat-keluar.master.templates.index'))
            ->assertOk();
    }

    public function test_role_selain_bidang_administrasi_ditolak_403(): void
    {
        $sekretaris = $this->createUserWithRole('sekretaris', 'Sekretaris');

        $this->actingAs($sekretaris)
            ->get(route('surat-keluar.master.letter-types.index'))
            ->assertForbidden();

        $this->actingAs($sekretaris)
            ->post(route('surat-keluar.master.letter-types.store'), [
                'code' => 'C.99',
                'name' => 'Surat Ilegal',
            ])
            ->assertForbidden();
    }

    public function test_crud_jenis_surat_dan_kode_tujuan_berjalan_normal(): void
    {
        $admin = $this->createUserWithRole('bidang-administrasi', 'Bidang Administrasi');

        // Create Jenis Surat
        $this->actingAs($admin)
            ->post(route('surat-keluar.master.letter-types.store'), [
                'code' => 'c.11',
                'name' => 'Surat Rekomendasi',
                'description' => 'Rekomendasi kader atau kegiatan',
                'is_active' => '1',
            ])
            ->assertRedirect(route('surat-keluar.master.letter-types.index'));

        $this->assertDatabaseHas('letter_types', [
            'code' => 'C.11',
            'name' => 'Surat Rekomendasi',
            'is_active' => true,
        ]);

        $letterType = LetterType::where('code', 'C.11')->firstOrFail();

        // Update Jenis Surat
        $this->actingAs($admin)
            ->put(route('surat-keluar.master.letter-types.update', $letterType), [
                'code' => 'C.11',
                'name' => 'Surat Rekomendasi Resmi',
                'description' => 'Updated',
            ])
            ->assertRedirect(route('surat-keluar.master.letter-types.index'));

        $this->assertDatabaseHas('letter_types', [
            'id' => $letterType->id,
            'name' => 'Surat Rekomendasi Resmi',
            'is_active' => false,
        ]);

        // Create Kode Tujuan
        $this->actingAs($admin)
            ->post(route('surat-keluar.master.destination-codes.store'), [
                'code' => 'J.999',
                'name' => 'PC Pemuda Persis Testing',
                'is_active' => '1',
            ])
            ->assertRedirect(route('surat-keluar.master.destination-codes.index'));

        $this->assertDatabaseHas('letter_destination_codes', [
            'code' => 'J.999',
        ]);
    }

    public function test_crud_template_dan_endpoint_json_fetch_api(): void
    {
        $admin = $this->createUserWithRole('bidang-administrasi', 'Bidang Administrasi');
        $sekretaris = $this->createUserWithRole('sekretaris', 'Sekretaris');

        $type = LetterType::create([
            'code' => 'C.6',
            'name' => 'Surat Undangan',
            'is_active' => true,
        ]);

        // Simpan template aktif & nonaktif
        $this->actingAs($admin)
            ->post(route('surat-keluar.master.templates.store'), [
                'letter_type_id' => $type->id,
                'name' => 'Template Undangan Musyawarah',
                'default_subject' => 'Undangan Musyawarah Kerja',
                'body_template' => 'Assalamualaikum, kami mengundang antum untuk hadir pada musyawarah kerja.',
                'is_active' => '1',
            ])
            ->assertRedirect(route('surat-keluar.master.templates.index'));

        LetterTemplate::create([
            'letter_type_id' => $type->id,
            'name' => 'Template Arsip Lama',
            'default_subject' => 'Lama',
            'body_template' => 'Isi template lama yang sudah dinonaktifkan.',
            'is_active' => false,
        ]);

        // Sekretaris memanggil Fetch API JSON: hanya template aktif yang muncul
        $this->actingAs($sekretaris)
            ->getJson(route('surat-keluar.api.templates.by-type', $type))
            ->assertOk()
            ->assertJsonPath('letter_type.code', 'C.6')
            ->assertJsonCount(1, 'templates')
            ->assertJsonPath('templates.0.name', 'Template Undangan Musyawarah');
    }
}