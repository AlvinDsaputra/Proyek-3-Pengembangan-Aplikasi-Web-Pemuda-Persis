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
            ['name' => 'Lihat Pengguna',         'slug' => 'shared.users.view',          'module' => 'SharedCore'],
            ['name' => 'Buat Pengguna',           'slug' => 'shared.users.create',        'module' => 'SharedCore'],
            ['name' => 'Edit Pengguna',           'slug' => 'shared.users.edit',          'module' => 'SharedCore'],
            ['name' => 'Aktifkan/Nonaktifkan',    'slug' => 'shared.users.toggle-active', 'module' => 'SharedCore'],
            // SharedCore — profil organisasi
            ['name' => 'Lihat Profil Organisasi', 'slug' => 'shared.org-profile.view',   'module' => 'SharedCore'],
            ['name' => 'Edit Profil Organisasi',  'slug' => 'shared.org-profile.edit',   'module' => 'SharedCore'],
            // SharedCore — periode kepengurusan
            ['name' => 'Kelola Periode',          'slug' => 'shared.periods.manage',      'module' => 'SharedCore'],
            // SharedCore — tanda tangan
            ['name' => 'Upload Tanda Tangan',     'slug' => 'shared.signature.upload',    'module' => 'SharedCore'],

            // SuratKeluar — master data (dikelola Bidang Administrasi)
            ['name' => 'Kelola Jenis Surat',      'slug' => 'surat.jenis.manage',         'module' => 'SuratKeluar'],
            ['name' => 'Kelola Kode Tujuan',      'slug' => 'surat.kode.manage',          'module' => 'SuratKeluar'],
            ['name' => 'Kelola Template Surat',   'slug' => 'surat.template.manage',      'module' => 'SuratKeluar'],
            // SuratKeluar — alur surat

            // DokumenAset — Surat Masuk (hanya Sekretaris; terpisah dari dokumen umum)
            ['name' => 'Catat Surat Masuk',       'slug' => 'surat-masuk.create',         'module' => 'DokumenAset'],
            ['name' => 'Lihat Surat',             'slug' => 'surat.view',                 'module' => 'SuratKeluar'],
            ['name' => 'Buat Draft Surat',        'slug' => 'surat.draft.create',         'module' => 'SuratKeluar'],
            ['name' => 'Edit Draft Surat',        'slug' => 'surat.draft.edit',           'module' => 'SuratKeluar'],
            ['name' => 'Hapus Draft Surat',       'slug' => 'surat.draft.delete',         'module' => 'SuratKeluar'],
            ['name' => 'Ajukan Surat',            'slug' => 'surat.draft.submit',         'module' => 'SuratKeluar'],
            ['name' => 'Setujui Surat',           'slug' => 'surat.approval.approve',     'module' => 'SuratKeluar'],

            // DokumenAset — Dokumen
            ['name' => 'Lihat Dokumen',           'slug' => 'dokumen.view',               'module' => 'DokumenAset'],
            ['name' => 'Upload Dokumen',          'slug' => 'dokumen.create',             'module' => 'DokumenAset'],
            ['name' => 'Edit Dokumen',            'slug' => 'dokumen.edit',               'module' => 'DokumenAset'],
            ['name' => 'Hapus Dokumen',           'slug' => 'dokumen.delete',             'module' => 'DokumenAset'],
            ['name' => 'Verifikasi Dokumen',      'slug' => 'dokumen.verify',             'module' => 'DokumenAset'],
            // DokumenAset — Aset
            ['name' => 'Lihat Aset',              'slug' => 'aset.view',                  'module' => 'DokumenAset'],
            ['name' => 'Buat Aset',               'slug' => 'aset.create',                'module' => 'DokumenAset'],
            ['name' => 'Edit Aset',               'slug' => 'aset.edit',                  'module' => 'DokumenAset'],
            ['name' => 'Hapus Aset',              'slug' => 'aset.delete',                'module' => 'DokumenAset'],

            // Kegiatan
            ['name' => 'Lihat Kegiatan',          'slug' => 'kegiatan.view',              'module' => 'Kegiatan'],
            ['name' => 'Buat Kegiatan',           'slug' => 'kegiatan.create',            'module' => 'Kegiatan'],
            ['name' => 'Edit Kegiatan',           'slug' => 'kegiatan.edit',              'module' => 'Kegiatan'],
            ['name' => 'Hapus Kegiatan',          'slug' => 'kegiatan.delete',            'module' => 'Kegiatan'],
            ['name' => 'Daftar Kegiatan',         'slug' => 'kegiatan.register',          'module' => 'Kegiatan'],
        ];

        $map = [];
        foreach ($definitions as $def) {
            $map[$def['slug']] = Permission::firstOrCreate(['slug' => $def['slug']], $def);
        }

        return $map;
    }

    private function createRoles(array $permissions): void
    {
        // Superadmin — kelola sistem, TIDAK bisa setujui surat
        $superadmin = Role::firstOrCreate(['slug' => 'superadmin'], [
            'name' => 'Super Admin',
            'description' => 'Administrator sistem; kelola pengguna, periode, profil organisasi',
        ]);
        $superadmin->permissions()->sync(
            collect($permissions)->except('surat.approval.approve')->pluck('id')
        );

        // Ketua — setujui surat, verifikasi dokumen, lihat semua; TIDAK edit konten
        $ketua = Role::firstOrCreate(['slug' => 'ketua'], [
            'name' => 'Ketua',
            'description' => 'Ketua PD Pemuda Persis',
        ]);
        $ketua->permissions()->sync(
            collect($permissions)->only([
                'shared.org-profile.view',
                'shared.signature.upload',
                'surat.view', 'surat.approval.approve',
                'dokumen.view', 'dokumen.verify',
                'aset.view',
                'kegiatan.view',
            ])->pluck('id')
        );

        // Sekretaris — buat/ajukan surat; lihat dokumen & kegiatan
        $sekretaris = Role::firstOrCreate(['slug' => 'sekretaris'], [
            'name' => 'Sekretaris',
            'description' => 'Sekretaris PD Pemuda Persis; bertanggung jawab surat menyurat',
        ]);
        $sekretaris->permissions()->sync(
            collect($permissions)->only([
                'shared.org-profile.view',
                'shared.signature.upload',
                'surat.view', 'surat.draft.create', 'surat.draft.edit',
                'surat.draft.delete', 'surat.draft.submit',
                'surat-masuk.create',       // hanya sekretaris yang catat surat masuk (BR-02 Tim 7)
                'dokumen.view',
                'aset.view',
                'kegiatan.view',
            ])->pluck('id')
        );

        // Bidang Administrasi — master data surat, kelola dokumen & aset
        $bidangAdmin = Role::firstOrCreate(['slug' => 'bidang_administrasi'], [
            'name' => 'Bidang Administrasi',
            'description' => 'Bidang Administrasi PD; bertanggung jawab master data surat, dokumen, arsip, dan aset',
        ]);
        $bidangAdmin->permissions()->sync(
            collect($permissions)->only([
                'shared.org-profile.view',
                'shared.signature.upload',
                'surat.jenis.manage', 'surat.kode.manage', 'surat.template.manage',
                'surat.view',
                'dokumen.view', 'dokumen.create', 'dokumen.edit', 'dokumen.delete',
                'aset.view', 'aset.create', 'aset.edit', 'aset.delete',
                'kegiatan.view',
            ])->pluck('id')
        );

        // Anggota — lihat publik + daftar kegiatan
        $anggota = Role::firstOrCreate(['slug' => 'anggota'], [
            'name' => 'Anggota',
            'description' => 'Anggota PD Pemuda Persis',
        ]);
        $anggota->permissions()->sync(
            collect($permissions)->only([
                'surat.view',
                'dokumen.view',
                'aset.view',
                'kegiatan.view', 'kegiatan.register',
            ])->pluck('id')
        );
    }
}
