<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => 'C.1',  'name' => 'Keputusan / Ketetapan', 'description' => 'Surat Keputusan atau Ketetapan resmi PD Pemuda Persis Kab. Bandung'],
            ['code' => 'C.2',  'name' => 'Keterangan / Mandat / Tugas / Rekomendasi', 'description' => 'Surat Keterangan, Mandat, Tugas, atau Rekomendasi pengurus'],
            ['code' => 'C.3',  'name' => 'Edaran / Pengumuman / Pernyataan / Pemberitahuan', 'description' => 'Surat Edaran, Pengumuman, Pernyataan Sikap, atau Pemberitahuan'],
            ['code' => 'C.4',  'name' => 'Instruksi / Perintah / Tausiyah', 'description' => 'Surat Instruksi, Perintah, atau Tausiyah organisasi'],
            ['code' => 'C.5',  'name' => 'Laporan', 'description' => 'Surat penyampaian laporan kegiatan atau kepengurusan'],
            ['code' => 'C.6',  'name' => 'Undangan', 'description' => 'Surat Undangan rapat, tautsiqul jamiyah, musyawarah, atau kegiatan'],
            ['code' => 'C.7',  'name' => 'Permohonan / Pengajuan', 'description' => 'Surat Permohonan izin, peminjaman tempat, narasumber, atau pengajuan'],
            ['code' => 'C.8',  'name' => 'Pengantar / Kiriman', 'description' => 'Surat Pengantar berkas atau pengiriman dokumen'],
            ['code' => 'C.9',  'name' => 'Mutasi', 'description' => 'Surat Mutasi keanggotaan atau kepengurusan'],
            ['code' => 'C.10', 'name' => 'Peringatan', 'description' => 'Surat Peringatan organisasi'],
        ];

        foreach ($types as $type) {
            DB::table('letter_types')->updateOrInsert(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'description' => $type['description'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $undanganId   = DB::table('letter_types')->where('code', 'C.6')->value('id');
        $tugasId      = DB::table('letter_types')->where('code', 'C.2')->value('id');
        $edaranId     = DB::table('letter_types')->where('code', 'C.3')->value('id');
        $permohonanId = DB::table('letter_types')->where('code', 'C.7')->value('id');

        $templates = [
            [
                'letter_type_id' => $undanganId,
                'name' => 'Template Surat Undangan (Standar PD)',
                'default_subject' => 'Undangan Tautsiqul Jamiyah / Rapat Koordinasi',
                'body_template' => "Puji syukur senantiasa kita panjatkan ke hadirat Allah SWT, atas limpahan rahmat dan maghfirah-Nya. Semoga setiap aktivitas kita mendapat ridla dan magfirah-Nya. Amin.\n\nDemi menjaga soliditas dan menguatkan gerak langkah jihad PD Pemuda Persis Kabupaten Bandung, maka kami akan menyelenggarakan [Nama Kegiatan] yang insya Allah akan dilaksanakan pada:\n\nHari/Tanggal : [Isi Hari & Tanggal Masehi / Hijriah]\nWaktu        : Pukul [Isi Jam] WIB s.d. Selesai\nTempat       : Kantor PD Pemuda Persis Kabupaten Bandung\n\nUntuk itu kami mengundang kepada [Pihak yang Diundang] supaya berkenan menghadiri acara tersebut karena penting adanya.\n\nDemikianlah surat ini kami sampaikan. Atas perhatiannya, kami ucapkan terima kasih, teriring do'a jazaa kumullahu khairan katsiran.",
            ],
            [
                'letter_type_id' => $tugasId,
                'name' => 'Template Surat Tugas / Mandat (C.2)',
                'default_subject' => 'Surat Tugas / Mandat Delegasi Kegiatan',
                'body_template' => "Puji syukur senantiasa kita panjatkan ke hadirat Allah SWT, atas limpahan rahmat dan maghfirah-Nya. Semoga setiap aktivitas kita mendapat ridla dan magfirah-Nya. Amin.\n\nPimpinan Daerah Pemuda Persatuan Islam Kabupaten Bandung dengan ini memberikan tugas/mandat kepada:\n\nNama    : [Isi Nama Lengkap]\nNPA     : [Isi NPA]\nJabatan : [Isi Jabatan Tasykil]\n\nUntuk menghadiri dan mewakili organisasi pada kegiatan:\nNama Kegiatan : [Isi Nama Kegiatan]\nHari/Tanggal  : [Isi Hari & Tanggal]\nTempat        : [Isi Lokasi Kegiatan]\n\nDemikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab. Teriring do'a jazaa kumullahu khairan katsiran.",
            ],
            [
                'letter_type_id' => $edaranId,
                'name' => 'Template Surat Edaran / Pemberitahuan (C.3)',
                'default_subject' => 'Pemberitahuan / Edaran Kegiatan Jam\'iyyah',
                'body_template' => "Puji syukur senantiasa kita panjatkan ke hadirat Allah SWT, atas limpahan rahmat dan maghfirah-Nya. Semoga setiap aktivitas kita mendapat ridla dan magfirah-Nya. Amin.\n\nSehubungan dengan akan dilaksanakannya [Nama Agenda/Program], bersama ini kami sampaikan pemberitahuan kepada seluruh [Tujuan Surat] mengenai hal-hal sebagai berikut:\n\n1. [Isi Poin Pemberitahuan Pertama]\n2. [Isi Poin Pemberitahuan Kedua]\n\nDemikian surat pemberitahuan ini kami sampaikan agar menjadi maklum. Atas perhatian dan kerjasamanya kami ucapkan jazaa kumullahu khairan katsiran.",
            ],
            [
                'letter_type_id' => $permohonanId,
                'name' => 'Template Surat Permohonan / Pengajuan (C.7)',
                'default_subject' => 'Permohonan Izin / Kerjasama Kegiatan',
                'body_template' => "Puji syukur senantiasa kita panjatkan ke hadirat Allah SWT, atas limpahan rahmat dan maghfirah-Nya. Semoga setiap aktivitas kita mendapat ridla dan magfirah-Nya. Amin.\n\nSehubungan dengan akan diselenggarakannya kegiatan [Nama Kegiatan] oleh PD Pemuda Persis Kabupaten Bandung, kami bermaksud mengajukan permohonan [Isi Keperluan Permohonan] pada:\n\nHari/Tanggal : [Isi Hari & Tanggal]\nWaktu        : Pukul [Isi Jam] WIB s.d. Selesai\nTempat       : [Isi Nama Tempat]\n\nBesar harapan kami permohonan ini dapat dikabulkan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih, teriring do'a jazaa kumullahu khairan katsiran.",
            ],
        ];

        foreach ($templates as $tpl) {
            if ($tpl['letter_type_id']) {
                DB::table('letter_templates')->updateOrInsert(
                    ['name' => $tpl['name']],
                    [
                        'letter_type_id' => $tpl['letter_type_id'],
                        'default_subject' => $tpl['default_subject'],
                        'body_template' => $tpl['body_template'],
                        'is_active' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}