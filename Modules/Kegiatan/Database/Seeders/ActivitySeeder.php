<?php

namespace Modules\Kegiatan\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Kegiatan\Models\Activity;
use Modules\Kegiatan\Models\Department;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $bidangKaderisasi = Department::firstOrCreate(
            ['slug' => 'bidang-kaderisasi'],
            ['name' => 'Bidang Kaderisasi', 'is_active' => true]
        );

        $bidangDakwah = Department::firstOrCreate(
            ['slug' => 'bidang-dakwah'],
            ['name' => 'Bidang Dakwah & Kajian', 'is_active' => true]
        );

        $bidangAdministrasi = Department::firstOrCreate(
            ['slug' => 'bidang-administrasi'],
            ['name' => 'Bidang Administrasi & Kesekretariatan', 'is_active' => true]
        );

        $creator = User::first();

        $activities = [
            [
                'slug'           => 'maruf-pelatihan-kader-muda-2026',
                'department_id'  => $bidangKaderisasi->id,
                'created_by'     => $creator?->id,
                'name'           => "Ma'ruf (Masa Ta'aruf) & Pelatihan Kader Muda Pemuda Persis",
                'speaker_name'   => 'Ust. Ahmad Fauzan, M.Pd.I',
                'location'       => 'Aula PD Pemuda Persis Kab. Bandung, Soreang',
                'contact_person' => '0812-3456-7890 (Akhi Raka)',
                'start_datetime' => now()->addDays(14)->setTime(8, 0),
                'end_datetime'   => now()->addDays(14)->setTime(16, 0),
                'quota'          => 100,
                'status'         => 'published',
                'description'    => "Program pengenalan organisasi dan penguatan ideologi kader baru Pemuda Persis dari 27 Pimpinan Cabang se-Kabupaten Bandung.\n\nMateri meliputi sejarah jamiyyah, adab berorganisasi, dan penguatan dakwah generasi muda.",
            ],
            [
                'slug'           => 'kajian-tematik-fiqh-dakwah-digital',
                'department_id'  => $bidangDakwah->id,
                'created_by'     => $creator?->id,
                'name'           => 'Kajian Tematik Bulanan: Fiqh Dakwah di Era Digital',
                'speaker_name'   => 'Ust. Dr. Ridwanulloh, Lc., M.A.',
                'location'       => 'Masjid Besar Persis Banjaran',
                'contact_person' => '0857-9988-1122 (Akhi Kasfa)',
                'start_datetime' => now()->addDays(7)->setTime(9, 0),
                'end_datetime'   => now()->addDays(7)->setTime(12, 0),
                'quota'          => 150,
                'status'         => 'published',
                'description'    => "Kajian bulanan terbuka bagi seluruh anggota dan tasykil Pimpinan Cabang untuk membahas strategi dakwah berbasis media digital secara santun dan berlandaskan Al-Qur'an serta As-Sunnah.",
            ],
            [
                'slug'           => 'bimtek-administrasi-persuratan-digital',
                'department_id'  => $bidangAdministrasi->id,
                'created_by'     => $creator?->id,
                'name'           => 'Bimbingan Teknis Tata Kelola Administrasi & Persuratan PC',
                'speaker_name'   => 'Habibillah & Tim Bidang Administrasi PD',
                'location'       => 'Gedung Dakwah Pemuda Persis, Ciparay',
                'contact_person' => '0813-2233-4455 (Sekretariat PD)',
                'start_datetime' => now()->addDays(21)->setTime(13, 0),
                'end_datetime'   => now()->addDays(21)->setTime(17, 0),
                'quota'          => 60,
                'status'         => 'published',
                'description'    => 'Pelatihan teknis bagi sekretaris dan bidang administrasi Pimpinan Cabang terkait standarisasi penomoran surat keluar, arsip digital, dan pencatatan kegiatan berbasis sistem web PD Pemuda Persis.',
            ],
        ];

        foreach ($activities as $data) {
            Activity::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
