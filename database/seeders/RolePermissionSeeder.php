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
            ['name' => 'Lihat Pengguna',        'slug' => 'shared.users.view',          'module' => 'SharedCore'],
            ['name' => 'Buat Pengguna',          'slug' => 'shared.users.create',        'module' => 'SharedCore'],
            ['name' => 'Edit Pengguna',          'slug' => 'shared.users.edit',          'module' => 'SharedCore'],
            ['name' => 'Aktifkan/Nonaktifkan',   'slug' => 'shared.users.toggle-active', 'module' => 'SharedCore'],
            // SharedCore — profil organisasi
            ['name' => 'Lihat Profil Organisasi','slug' => 'shared.org-profile.view',    'module' => 'SharedCore'],
            ['name' => 'Edit Profil Organisasi', 'slug' => 'shared.org-profile.edit',    'module' => 'SharedCore'],
            // SharedCore — periode kepengurusan
            ['name' => 'Kelola Periode',         'slug' => 'shared.periods.manage',      'module' => 'SharedCore'],
            // SharedCore — tanda tangan
            ['name' => 'Upload Tanda Tangan',    'slug' => 'shared.signature.upload',    'module' => 'SharedCore'],
            // SuratKeluar
            ['name' => 'Lihat Surat',            'slug' => 'surat.view',                 'module' => 'SuratKeluar'],
            ['name' => 'Buat Draft Surat',       'slug' => 'surat.draft.create',         'module' => 'SuratKeluar'],
            ['name' => 'Edit Draft Surat',       'slug' => 'surat.draft.edit',           'module' => 'SuratKeluar'],
            ['name' => 'Hapus Draft Surat',      'slug' => 'surat.draft.delete',         'module' => 'SuratKeluar'],
            ['name' => 'Ajukan Surat',           'slug' => 'surat.draft.submit',         'module' => 'SuratKeluar'],
            ['name' => 'Setujui Surat',          'slug' => 'surat.approval.approve',     'module' => 'SuratKeluar'],
            // DokumenAset
            ['name' => 'Lihat Aset',             'slug' => 'aset.view',                  'module' => 'DokumenAset'],
            ['name' => 'Buat Aset',              'slug' => 'aset.create',                'module' => 'DokumenAset'],
            ['name' => 'Edit Aset',              'slug' => 'aset.edit',                  'module' => 'DokumenAset'],
            ['name' => 'Hapus Aset',             'slug' => 'aset.delete',                'module' => 'DokumenAset'],
            // Kegiatan
            ['name' => 'Lihat Kegiatan',         'slug' => 'kegiatan.view',              'module' => 'Kegiatan'],
            ['name' => 'Buat Kegiatan',          'slug' => 'kegiatan.create',            'module' => 'Kegiatan'],
            ['name' => 'Edit Kegiatan',          'slug' => 'kegiatan.edit',              'module' => 'Kegiatan'],
            ['name' => 'Hapus Kegiatan',         'slug' => 'kegiatan.delete',            'module' => 'Kegiatan'],
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
            'description' => 'Administrator sistem',
        ]);
        $superadmin->permissions()->sync(
            collect($permissions)
                ->except('surat.approval.approve')
                ->pluck('id')
        );

        // Ketua — bisa setujui surat, lihat semua
        $ketua = Role::firstOrCreate(['slug' => 'ketua'], [
            'name' => 'Ketua',
            'description' => 'Ketua PD Pemuda Persis',
        ]);
        $ketua->permissions()->sync(
            collect($permissions)
                ->only([
                    'shared.org-profile.view',
                    'shared.signature.upload',
                    'surat.view', 'surat.approval.approve',
                    'aset.view',
                    'kegiatan.view',
                ])
                ->pluck('id')
        );

        // Sekretaris — buat dan ajukan surat
        $sekretaris = Role::firstOrCreate(['slug' => 'sekretaris'], [
            'name' => 'Sekretaris',
            'description' => 'Sekretaris PD Pemuda Persis',
        ]);
        $sekretaris->permissions()->sync(
            collect($permissions)
                ->only([
                    'shared.org-profile.view',
                    'shared.signature.upload',
                    'surat.view', 'surat.draft.create', 'surat.draft.edit',
                    'surat.draft.delete', 'surat.draft.submit',
                    'aset.view',
                    'kegiatan.view',
                ])
                ->pluck('id')
        );

        // Anggota — hanya lihat
        $anggota = Role::firstOrCreate(['slug' => 'anggota'], [
            'name' => 'Anggota',
            'description' => 'Anggota PD Pemuda Persis',
        ]);
        $anggota->permissions()->sync(
            collect($permissions)
                ->only(['surat.view', 'aset.view', 'kegiatan.view'])
                ->pluck('id')
        );
    }
}
