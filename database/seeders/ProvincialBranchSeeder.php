<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SharedCore\Models\ProvincialBranch;

class ProvincialBranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            // -------------------------------------------------------
            // ISI DATA DI BAWAH INI
            // Format: ['name' => '...', 'code' => '..', 'region' => 'Kab. Bandung']
            // Urutkan sesuai nomor kode dari indeks surat
            // -------------------------------------------------------

            ['name' => 'PC Baleendah', 'code' => '16', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Banjaran', 'code' => '17', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Ciwidey', 'code' => '21', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Bojongsoang', 'code' => '22', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Dayeuhkolot', 'code' => '23', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Ciparay', 'code' => '24', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Pameungpeuk', 'code' => '25', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Rancaekek', 'code' => '27', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Soreang', 'code' => '28', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Kutawaringin', 'code' => '29', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Margaasih', 'code' => '30', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Katapang', 'code' => '32', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Pangalengan', 'code' => '98', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Arjasari', 'code' => '113', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Cicalengka', 'code' => '117', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Paseh', 'code' => '118', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Margahayu', 'code' => '121', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Majalaya', 'code' => '124', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Cimaung', 'code' => '136', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Kertasari', 'code' => '138', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Cileunyi', 'code' => '139', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Cangkuang', 'code' => '147', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Cimenyan', 'code' => '148', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Pasir Jambu', 'code' => '149', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Rancabali', 'code' => '151', 'region' => 'Kab. Bandung'],
            ['name' => 'PC Solokan Jeruk', 'code' => '190', 'region' => 'Kab. Bandung'],
            // PC Ibun — tidak ada kode di indeks surat; gunakan '00' sebagai penanda
            ['name' => 'PC Ibun', 'code' => '00', 'region' => 'Kab. Bandung'],
        ];

        foreach ($branches as $data) {
            ProvincialBranch::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
