<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LetterDestinationCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            // Umum & Jajaran Jam'iyyah
            ['code' => 'PP',    'name' => 'PP Persatuan Islam', 'description' => 'Pimpinan Pusat Persatuan Islam'],
            ['code' => 'JJ',    'name' => "Jajaran Jam'iyyah (PW, PD, PC)", 'description' => "Seluruh jajaran jam'iyyah PW, PD, atau PC"],

            // A. Majelis, Dewan, & Bagian Otonom PP Persis
            ['code' => 'A.1',   'name' => 'Majelis Penasihat', 'description' => 'Majelis Penasihat PP Persis'],
            ['code' => 'A.2',   'name' => 'Dewan Hisbah', 'description' => 'Dewan Hisbah PP Persis'],
            ['code' => 'A.3',   'name' => 'Dewan Hisab dan Rukyat', 'description' => 'Dewan Hisab dan Rukyat PP Persis'],
            ['code' => 'A.4',   'name' => 'Dewan Tafkir', 'description' => 'Dewan Tafkir PP Persis'],
            ['code' => 'A.5',   'name' => 'Lajnah Bantuan Hukum', 'description' => 'Lajnah Bantuan Hukum PP Persis'],
            ['code' => 'A.6',   'name' => 'Bagian Otonom PP Persis', 'description' => 'Bagian Otonom di lingkungan PP Persis'],
            ['code' => 'A.6.1', 'name' => 'PP Persistri', 'description' => 'Pimpinan Pusat Persatuan Islam Istri'],
            ['code' => 'A.6.2', 'name' => 'PP Pemuda Persis', 'description' => 'Pimpinan Pusat Pemuda Persatuan Islam'],
            ['code' => 'A.6.3', 'name' => 'PP Pemudi Persis', 'description' => 'Pimpinan Pusat Pemudi Persatuan Islam'],
            ['code' => 'A.6.4', 'name' => 'PP HIMA Persis', 'description' => 'Pimpinan Pusat Himpunan Mahasiswa Persis'],
            ['code' => 'A.6.5', 'name' => 'PP HIMI Persis', 'description' => 'Pimpinan Pusat Himpunan Mahasiswi Persis'],

            // B. Lain-Lain
            ['code' => 'B.1',   'name' => 'Anggota Pimpinan', 'description' => 'Anggota Pimpinan / Tasykil'],
            ['code' => 'B.2',   'name' => 'Kepanitiaan', 'description' => 'Panitia Kegiatan'],
            ['code' => 'B.3',   'name' => 'Perorangan', 'description' => 'Perorangan / Individu'],
            ['code' => 'B.4',   'name' => 'Lembaga Khusus Pemuda Persis', 'description' => 'Lembaga khusus di bawah Pemuda Persis'],

            // D. Orsos / Orpol / Ormas
            ['code' => 'D.1',   'name' => 'Orsos/Orpol/Ormas Islam', 'description' => 'Organisasi Islam'],
            ['code' => 'D.2',   'name' => 'Orsos/Orpol/Ormas Umum', 'description' => 'Organisasi Umum'],
            ['code' => 'D.3',   'name' => 'Persis dan Otonom di Lingkungan PW/PD/PC/PJ', 'description' => 'Persis dan otonom tingkat wilayah hingga jamaah'],

            // E. Pemerintah
            ['code' => 'E.1',   'name' => 'Presiden / Wakil Presiden', 'description' => 'Pemerintah Pusat'],
            ['code' => 'E.2',   'name' => 'Menteri / Departemen / Direktorat', 'description' => 'Kementerian atau Direktorat'],
            ['code' => 'E.3',   'name' => 'MPR / DPR / DPRD', 'description' => 'Lembaga Legislatif'],
            ['code' => 'E.4',   'name' => 'Instansi / Jawatan / Kantor / Dinas', 'description' => 'Dinas atau Kantor Pemerintah'],
            ['code' => 'E.5',   'name' => 'Gubernur / Bupati / Walikota / Camat / Lurah / Kades / RW / RT', 'description' => 'Kepala Daerah hingga Pemerintahan Desa/Kelurahan'],

            // F. Militer / Kepolisian
            ['code' => 'F.1',   'name' => 'Markas Besar', 'description' => 'Markas Besar TNI / Polri'],
            ['code' => 'F.2',   'name' => 'Kodam / Kodau / Kodamar / Polda', 'description' => 'Komando / Kepolisian Daerah'],
            ['code' => 'F.3',   'name' => 'Korem / Kodim / Koramil / Polwil / Polres / Polsek', 'description' => 'Komando / Kepolisian Resor hingga Sektor'],

            // H & I. Wilayah & Daerah Utama
            ['code' => 'H.1',   'name' => 'Pimpinan Wilayah Jawa Barat', 'description' => 'PW Pemuda Persis Jawa Barat'],
            ['code' => 'I.1',   'name' => 'Pimpinan Daerah Kota Bandung', 'description' => 'PD Pemuda Persis Kota Bandung'],
            ['code' => 'I.2',   'name' => 'Pimpinan Daerah Kabupaten Bandung', 'description' => 'PD Pemuda Persis Kabupaten Bandung'],
        ];

        // Tarik otomatis 26 PC Kab. Bandung (kecuali '00' PC Ibun yang belum punya kode resmi)
        $branches = DB::table('provincial_branches')
            ->whereNotNull('code')
            ->where('code', '!=', '00')
            ->get();

        foreach ($branches as $branch) {
            $codes[] = [
                'code' => 'J.' . $branch->code,
                'name' => $branch->name,
                'description' => 'PC Pemuda Persis wilayah ' . ($branch->region ?? 'Kab. Bandung'),
            ];
        }

        foreach ($codes as $item) {
            DB::table('letter_destination_codes')->updateOrInsert(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}