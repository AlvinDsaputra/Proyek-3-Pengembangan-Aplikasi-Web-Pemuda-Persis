<?php

namespace Tests\Feature\Kegiatan;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Kegiatan\Models\Activity;
use Modules\Kegiatan\Models\Department;
use Tests\TestCase;

class PublicActivityTest extends TestCase
{
    use RefreshDatabase;

    private function createDepartment(string $name, string $slug): Department
    {
        return Department::create([
            'name'      => $name,
            'slug'      => $slug,
            'is_active' => true,
        ]);
    }

    private function createActivity(Department $department, array $overrides = []): Activity
    {
        return Activity::create(array_merge([
            'department_id'  => $department->id,
            'name'           => 'Kajian Pemuda Persis',
            'slug'           => 'kajian-pemuda-persis-' . uniqid(),
            'speaker_name'   => 'Ust. Ahmad Fauzan',
            'location'       => 'Aula PD Pemuda Persis, Soreang',
            'contact_person' => '0812-0000-1111',
            'start_datetime' => now()->addDays(5),
            'end_datetime'   => now()->addDays(5)->addHours(3),
            'quota'          => 100,
            'status'         => 'published',
            'description'    => 'Deskripsi kegiatan uji coba.',
        ], $overrides));
    }

    public function test_katalog_hanya_menampilkan_kegiatan_berstatus_published(): void
    {
        $dept = $this->createDepartment('Bidang Dakwah', 'bidang-dakwah');

        $published = $this->createActivity($dept, [
            'name'   => 'Kajian Akbar Terbuka',
            'status' => 'published',
        ]);

        $draft = $this->createActivity($dept, [
            'name'   => 'Rapat Internal Rahasia',
            'status' => 'draft',
        ]);

        $response = $this->get(route('kegiatan.index'));

        $response->assertOk()
            ->assertSee($published->name)
            ->assertSee($dept->name)
            ->assertDontSee($draft->name);
    }

    public function test_pencarian_dan_filter_bidang_di_katalog_berjalan(): void
    {
        $dakwah = $this->createDepartment('Bidang Dakwah', 'bidang-dakwah');
        $kaderisasi = $this->createDepartment('Bidang Kaderisasi', 'bidang-kaderisasi');

        $this->createActivity($dakwah, ['name' => 'Kajian Fiqh Kontemporer']);
        $this->createActivity($kaderisasi, ['name' => "Ma'ruf Kader Muda"]);

        $this->get(route('kegiatan.index', ['department' => $kaderisasi->id]))
            ->assertOk()
            ->assertSee("Ma'ruf Kader Muda")
            ->assertDontSee('Kajian Fiqh Kontemporer');

        $this->get(route('kegiatan.index', ['search' => 'Fiqh']))
            ->assertOk()
            ->assertSee('Kajian Fiqh Kontemporer')
            ->assertDontSee("Ma'ruf Kader Muda");
    }

    public function test_halaman_detail_menampilkan_info_lengkap_dan_menolak_draft(): void
    {
        $dept = $this->createDepartment('Bidang Administrasi', 'bidang-administrasi');

        $published = $this->createActivity($dept, [
            'name'           => 'Bimtek Persuratan Digital',
            'speaker_name'   => 'Habibillah',
            'contact_person' => '0813-2233-4455',
            'status'         => 'published',
        ]);

        $draft = $this->createActivity($dept, [
            'name'   => 'Kegiatan Masih Draf',
            'status' => 'draft',
        ]);

        $this->get(route('kegiatan.show', $published->id))
            ->assertOk()
            ->assertSee('Bimtek Persuratan Digital')
            ->assertSee('Bidang Administrasi')
            ->assertSee('Habibillah')
            ->assertSee('0813-2233-4455')
            ->assertSee('Daftar Kegiatan (1-Klik)');

        $this->get(route('kegiatan.show', $draft->id))
            ->assertNotFound();
    }

    public function test_user_yang_sudah_login_dapat_mengakses_katalog_dan_detail(): void
    {
        $dept = $this->createDepartment('Bidang Kaderisasi', 'bidang-kaderisasi');
        $activity = $this->createActivity($dept);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('kegiatan.index'))
            ->assertOk()
            ->assertSee($activity->name);

        $this->actingAs($user)
            ->get(route('kegiatan.show', $activity->id))
            ->assertOk()
            ->assertSee($activity->name);
    }
}
