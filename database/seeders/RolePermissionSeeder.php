<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SharedCore\Models\Permission;
use Modules\SharedCore\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = $this->createPermissions();
        $this->createRoles($permissions);
    }

    private function createPermissions(): array
    {
        $definitions = [
            // SharedCore — manajemen pengguna
            ['name' => 'Lihat Pengguna',              'slug' => 'shared.users.view',              'module' => 'SharedCore'],
            ['name' => 'Buat Pengguna',               'slug' => 'shared.users.create',            'module' => 'SharedCore'],
            ['name' => 'Edit Pengguna',               'slug' => 'shared.users.edit',              'module' => 'SharedCore'],
            ['name' => 'Aktifkan/Nonaktifkan',        'slug' => 'shared.users.toggle-active',     'module' => 'SharedCore'],
            // SharedCore — profil organisasi
            ['name' => 'Lihat Profil Organisasi',     'slug' => 'shared.org-profile.view',        'module' => 'SharedCore'],
            ['name' => 'Edit Profil Organisasi',      'slug' => 'shared.org-profile.edit',        'module' => 'SharedCore'],
            // SharedCore — periode kepengurusan
            ['name' => 'Kelola Periode',              'slug' => 'shared.periods.manage',          'module' => 'SharedCore'],
            // SharedCore — tanda tangan (hanya Ketua & Sekretaris)
            ['name' => 'Upload Tanda Tangan',         'slug' => 'shared.signature.upload',        'module' => 'SharedCore'],

            // SuratKeluar — master data (dikelola Bidang Administrasi & Sekretaris)
            ['name' => 'Kelola Jenis Surat',          'slug' => 'surat.jenis.manage',             'module' => 'SuratKeluar'],
            ['name' => 'Kelola Kode Tujuan',          'slug' => 'surat.kode.manage',              'module' => 'SuratKeluar'],
            ['name' => 'Kelola Template Surat',       'slug' => 'surat.template.manage',          'module' => 'SuratKeluar'],
            // SuratKeluar — alur surat
            ['name' => 'Lihat Surat',                 'slug' => 'surat.view',                     'module' => 'SuratKeluar'],
            ['name' => 'Buat Draft Surat',            'slug' => 'surat.draft.create',             'module' => 'SuratKeluar'],
            ['name' => 'Edit Draft Surat',            'slug' => 'surat.draft.edit',               'module' => 'SuratKeluar'],
            ['name' => 'Hapus Draft Surat',           'slug' => 'surat.draft.delete',             'module' => 'SuratKeluar'],
            ['name' => 'Ajukan Surat',                'slug' => 'surat.draft.submit',             'module' => 'SuratKeluar'],
            ['name' => 'Setujui Surat',               'slug' => 'surat.approval.approve',         'module' => 'SuratKeluar'],

            // DokumenAset — Surat Masuk
            ['name' => 'Catat Surat Masuk',           'slug' => 'surat-masuk.create',             'module' => 'DokumenAset'],
            ['name' => 'Update Status Surat Masuk',   'slug' => 'surat-masuk.status.update',      'module' => 'DokumenAset'],

            // DokumenAset — Dokumen
            ['name' => 'Lihat Dokumen',               'slug' => 'dokumen.view',                   'module' => 'DokumenAset'],
            ['name' => 'Upload Dokumen',              'slug' => 'dokumen.create',                 'module' => 'DokumenAset'],
            ['name' => 'Edit Dokumen',                'slug' => 'dokumen.edit',                   'module' => 'DokumenAset'],
            ['name' => 'Hapus Dokumen',               'slug' => 'dokumen.delete',                 'module' => 'DokumenAset'],
            ['name' => 'Atur Visibilitas Dokumen',    'slug' => 'dokumen.set-visibility',         'module' => 'DokumenAset'],
            ['name' => 'Kelola Kategori Dokumen',     'slug' => 'dokumen.kategori.manage',        'module' => 'DokumenAset'],
            ['name' => 'Lihat Laporan Dokumen',       'slug' => 'dokumen.report',                 'module' => 'DokumenAset'],

            // DokumenAset — Aset
            ['name' => 'Lihat Aset',                  'slug' => 'aset.view',                      'module' => 'DokumenAset'],
            ['name' => 'Buat Aset',                   'slug' => 'aset.create',                    'module' => 'DokumenAset'],
            ['name' => 'Edit Aset',                   'slug' => 'aset.edit',                      'module' => 'DokumenAset'],
            ['name' => 'Hapus Aset',                  'slug' => 'aset.delete',                    'module' => 'DokumenAset'],
            ['name' => 'Cetak Label Aset',            'slug' => 'aset.label',                     'module' => 'DokumenAset'],

            // Kegiatan
            ['name' => 'Lihat Kegiatan',              'slug' => 'kegiatan.view',                  'module' => 'Kegiatan'],
            ['name' => 'Buat Kegiatan',               'slug' => 'kegiatan.create',                'module' => 'Kegiatan'],
            ['name' => 'Edit Kegiatan',               'slug' => 'kegiatan.edit',                  'module' => 'Kegiatan'],
            ['name' => 'Hapus Kegiatan',              'slug' => 'kegiatan.delete',                'module' => 'Kegiatan'],
            ['name' => 'Daftar Kegiatan',             'slug' => 'kegiatan.register',              'module' => 'Kegiatan'],
            ['name' => 'Kelola Absensi Kegiatan',     'slug' => 'kegiatan.attendance.manage',     'module' => 'Kegiatan'],
            ['name' => 'Lihat Laporan Kegiatan',      'slug' => 'kegiatan.report.view',           'module' => 'Kegiatan'],
        ];

        $map = [];
        foreach ($definitions as $def) {
            $map[$def['slug']] = Permission::firstOrCreate(['slug' => $def['slug']], $def);
        }

        return $map;
    }

    private function createRoles(array $permissions): void
    {
        // Superadmin — HANYA kelola akun, periode, profil organisasi + read-only modul lain
        // Tidak boleh: setujui surat, upload tanda tangan, kelola master data surat,
        // buat/ubah/hapus dokumen/aset/kegiatan, atau lakukan rekonsiliasi
        $superadmin = Role::firstOrCreate(['slug' => 'superadmin'], [
            'name' => 'Super Admin',
            'description' => 'Administrator sistem; kelola pengguna, periode, profil organisasi (read-only di modul lain)',
        ]);
        $superadmin->permissions()->sync(
            collect($permissions)->only([
                // Core: manajemen akun
                'shared.users.view', 'shared.users.create',
                'shared.users.edit', 'shared.users.toggle-active',
                // Core: profil organisasi
                'shared.org-profile.view', 'shared.org-profile.edit',
                // Core: periode kepengurusan
                'shared.periods.manage',
                // Read-only oversight (tidak bisa buat/ubah/hapus)
                'surat.view',
                'dokumen.view', 'dokumen.report',
                'aset.view',
                'kegiatan.view', 'kegiatan.report.view',
            ])->pluck('id')
        );

        // Ketua — setujui surat, lihat semua; TIDAK edit konten
        $ketua = Role::firstOrCreate(['slug' => 'ketua'], [
            'name' => 'Ketua',
            'description' => 'Ketua PD Pemuda Persis',
        ]);
        $ketua->permissions()->sync(
            collect($permissions)->only([
                'shared.org-profile.view',
                'shared.signature.upload',
                'surat.view', 'surat.approval.approve',
                'dokumen.view', 'dokumen.report',
                'aset.view',
                'kegiatan.view', 'kegiatan.report.view',
            ])->pluck('id')
        );

        // Sekretaris — buat/ajukan surat, kelola dokumen, catat surat masuk
        $sekretaris = Role::firstOrCreate(['slug' => 'sekretaris'], [
            'name' => 'Sekretaris',
            'description' => 'Sekretaris PD Pemuda Persis; bertanggung jawab surat menyurat dan dokumen',
        ]);
        $sekretaris->permissions()->sync(
            collect($permissions)->only([
                'shared.org-profile.view',
                'shared.signature.upload',
                // Surat Keluar
                'surat.template.manage',
                'surat.view', 'surat.draft.create', 'surat.draft.edit',
                'surat.draft.delete', 'surat.draft.submit',
                // Surat Masuk
                'surat-masuk.create', 'surat-masuk.status.update',
                // Dokumen
                'dokumen.view', 'dokumen.create', 'dokumen.edit', 'dokumen.delete',
                'dokumen.set-visibility', 'dokumen.kategori.manage', 'dokumen.report',
                // Aset
                'aset.view', 'aset.edit', 'aset.delete',
                // Kegiatan
                'kegiatan.view', 'kegiatan.create', 'kegiatan.edit', 'kegiatan.delete',
                'kegiatan.report.view',
            ])->pluck('id')
        );

        // Bidang Administrasi — master data surat, unggah dokumen, kelola aset, absensi kegiatan
        $bidangAdmin = Role::firstOrCreate(['slug' => 'bidang_administrasi'], [
            'name' => 'Bidang Administrasi',
            'description' => 'Bidang Administrasi PD; master data surat, dokumen, arsip, aset, absensi kegiatan',
        ]);
        $bidangAdmin->permissions()->sync(
            collect($permissions)->only([
                'shared.org-profile.view',
                // Master data surat (dipindah dari Superadmin)
                'surat.jenis.manage', 'surat.kode.manage', 'surat.template.manage',
                'surat.view',
                // Dokumen — hanya upload; edit/hapus oleh Sekretaris
                'dokumen.view', 'dokumen.create', 'dokumen.kategori.manage', 'dokumen.report',
                // Aset
                'aset.view', 'aset.create', 'aset.edit', 'aset.label',
                // Kegiatan
                'kegiatan.view', 'kegiatan.create', 'kegiatan.edit',
                'kegiatan.attendance.manage', 'kegiatan.report.view',
            ])->pluck('id')
        );

        // Anggota/Peserta — lihat publik + daftar kegiatan
        $anggota = Role::firstOrCreate(['slug' => 'anggota'], [
            'name' => 'Anggota',
            'description' => 'Anggota PD Pemuda Persis',
        ]);
        $anggota->permissions()->sync(
            collect($permissions)->only([
                'kegiatan.view', 'kegiatan.register',
            ])->pluck('id')
        );
    }
}
