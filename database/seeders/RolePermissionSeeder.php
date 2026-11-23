<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SharedCore\Models\Permission;
use Modules\SharedCore\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = $this->syncPermissions();
        $this->syncRoles($permissions);
    }

    private function syncPermissions(): array
    {
        $definitions = [
            ['name' => 'Lihat Pengguna', 'slug' => 'shared.users.view', 'module' => 'SharedCore'],
            ['name' => 'Buat Pengguna', 'slug' => 'shared.users.create', 'module' => 'SharedCore'],
            ['name' => 'Edit Pengguna', 'slug' => 'shared.users.edit', 'module' => 'SharedCore'],
            ['name' => 'Aktifkan/Nonaktifkan', 'slug' => 'shared.users.toggle-active', 'module' => 'SharedCore'],
            ['name' => 'Lihat Profil Organisasi', 'slug' => 'shared.org-profile.view', 'module' => 'SharedCore'],
            ['name' => 'Edit Profil Organisasi', 'slug' => 'shared.org-profile.edit', 'module' => 'SharedCore'],
            ['name' => 'Kelola Periode', 'slug' => 'shared.periods.manage', 'module' => 'SharedCore'],
            ['name' => 'Upload Tanda Tangan', 'slug' => 'shared.signature.upload', 'module' => 'SharedCore'],

            ['name' => 'Kelola Jenis Surat', 'slug' => 'surat.jenis.manage', 'module' => 'SuratKeluar'],
            ['name' => 'Kelola Kode Tujuan', 'slug' => 'surat.kode.manage', 'module' => 'SuratKeluar'],
            ['name' => 'Kelola Template Surat', 'slug' => 'surat.template.manage', 'module' => 'SuratKeluar'],
            ['name' => 'Lihat Surat', 'slug' => 'surat.view', 'module' => 'SuratKeluar'],
            ['name' => 'Buat Draft Surat', 'slug' => 'surat.draft.create', 'module' => 'SuratKeluar'],
            ['name' => 'Edit Draft Surat', 'slug' => 'surat.draft.edit', 'module' => 'SuratKeluar'],
            ['name' => 'Hapus Draft Surat', 'slug' => 'surat.draft.delete', 'module' => 'SuratKeluar'],
            ['name' => 'Ajukan Surat', 'slug' => 'surat.draft.submit', 'module' => 'SuratKeluar'],
            ['name' => 'Setujui Surat', 'slug' => 'surat.approval.approve', 'module' => 'SuratKeluar'],
            ['name' => 'Lihat Log Persetujuan Surat', 'slug' => 'surat.approval-log.view', 'module' => 'SuratKeluar'],

            ['name' => 'Catat Surat Masuk', 'slug' => 'surat-masuk.create', 'module' => 'DokumenAset'],
            ['name' => 'Ubah Status Surat Masuk', 'slug' => 'surat-masuk.status.update', 'module' => 'DokumenAset'],
            ['name' => 'Lihat Dokumen', 'slug' => 'dokumen.view', 'module' => 'DokumenAset'],
            ['name' => 'Upload Dokumen', 'slug' => 'dokumen.create', 'module' => 'DokumenAset'],
            ['name' => 'Edit Dokumen', 'slug' => 'dokumen.edit', 'module' => 'DokumenAset'],
            ['name' => 'Hapus Dokumen', 'slug' => 'dokumen.delete', 'module' => 'DokumenAset'],
            ['name' => 'Atur Visibilitas Dokumen', 'slug' => 'dokumen.set-visibility', 'module' => 'DokumenAset'],
            ['name' => 'Kelola Kategori Dokumen', 'slug' => 'dokumen.kategori.manage', 'module' => 'DokumenAset'],
            ['name' => 'Lihat Laporan Dokumen', 'slug' => 'dokumen.report', 'module' => 'DokumenAset'],
            ['name' => 'Lihat Aset', 'slug' => 'aset.view', 'module' => 'DokumenAset'],
            ['name' => 'Buat Aset', 'slug' => 'aset.create', 'module' => 'DokumenAset'],
            ['name' => 'Edit Aset', 'slug' => 'aset.edit', 'module' => 'DokumenAset'],
            ['name' => 'Hapus Aset', 'slug' => 'aset.delete', 'module' => 'DokumenAset'],
            ['name' => 'Cetak Label Aset', 'slug' => 'aset.label', 'module' => 'DokumenAset'],

            ['name' => 'Lihat Kegiatan', 'slug' => 'kegiatan.view', 'module' => 'Kegiatan'],
            ['name' => 'Buat Kegiatan', 'slug' => 'kegiatan.create', 'module' => 'Kegiatan'],
            ['name' => 'Edit Kegiatan', 'slug' => 'kegiatan.edit', 'module' => 'Kegiatan'],
            ['name' => 'Hapus Kegiatan', 'slug' => 'kegiatan.delete', 'module' => 'Kegiatan'],
            ['name' => 'Daftar Kegiatan', 'slug' => 'kegiatan.register', 'module' => 'Kegiatan'],
            ['name' => 'Kelola Kehadiran Kegiatan', 'slug' => 'kegiatan.attendance.manage', 'module' => 'Kegiatan'],
            ['name' => 'Lihat Laporan Kegiatan', 'slug' => 'kegiatan.report.view', 'module' => 'Kegiatan'],
        ];

        $map = [];
        foreach ($definitions as $definition) {
            $map[$definition['slug']] = Permission::updateOrCreate(
                ['slug' => $definition['slug']],
                $definition
            );
        }

        return $map;
    }

    private function syncRoles(array $permissions): void
    {
        $roleDefinitions = [
            // Super Admin hanya mengelola sistem dan audit log, tanpa akses tanda tangan atau operasional modul.
            'superadmin' => [
                'name' => 'Super Admin',
                'description' => 'Administrator sistem; kelola pengguna, periode, profil organisasi',
                'permissions' => [
                    'shared.users.view',
                    'shared.users.create',
                    'shared.users.edit',
                    'shared.users.toggle-active',
                    'shared.org-profile.view',
                    'shared.org-profile.edit',
                    'shared.periods.manage',
                    'surat.approval-log.view',
                ],
            ],
            'ketua' => [
                'name' => 'Ketua',
                'description' => 'Ketua PD Pemuda Persis',
                'permissions' => [
                    'shared.org-profile.view',
                    'shared.signature.upload',
                    'surat.view',
                    'surat.approval.approve',
                    'surat.approval-log.view',
                    'dokumen.view',
                    'dokumen.report',
                    'aset.view',
                    'kegiatan.view',
                    'kegiatan.report.view',
                ],
            ],
            'sekretaris' => [
                'name' => 'Sekretaris',
                'description' => 'Sekretaris PD Pemuda Persis; bertanggung jawab surat menyurat',
                'permissions' => [
                    'shared.org-profile.view',
                    'shared.signature.upload',
                    'surat.view',
                    'surat.draft.create',
                    'surat.draft.edit',
                    'surat.draft.delete',
                    'surat.draft.submit',
                    'surat.approval-log.view',
                    'surat-masuk.create',
                    'surat-masuk.status.update',
                    'dokumen.view',
                    'dokumen.create',
                    'dokumen.edit',
                    'dokumen.delete',
                    'dokumen.set-visibility',
                    'dokumen.kategori.manage',
                    'dokumen.report',
                    'aset.view',
                    'aset.delete',
                    'kegiatan.view',
                    'kegiatan.create',
                    'kegiatan.edit',
                    'kegiatan.delete',
                ],
            ],
            // Bidang Administrasi hanya mengelola master surat, tanpa akses arsip surat keluar dan tanda tangan.
            'bidang_administrasi' => [
                'name' => 'Bidang Administrasi',
                'description' => 'Bidang Administrasi PD; bertanggung jawab master data surat, dokumen, arsip, dan aset',
                'permissions' => [
                    'shared.org-profile.view',
                    'surat.jenis.manage',
                    'surat.kode.manage',
                    'surat.template.manage',
                    'dokumen.view',
                    'dokumen.create',
                    'dokumen.kategori.manage',
                    'aset.view',
                    'aset.create',
                    'aset.edit',
                    'aset.label',
                    'kegiatan.view',
                    'kegiatan.attendance.manage',
                    'kegiatan.report.view',
                ],
            ],
            'anggota' => [
                'name' => 'Anggota',
                'description' => 'Anggota PD Pemuda Persis',
                'permissions' => [
                    'dokumen.view',
                    'kegiatan.view',
                    'kegiatan.register',
                ],
            ],
        ];

        $permissionCollection = collect($permissions);

        foreach ($roleDefinitions as $slug => $config) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $config['name'],
                    'description' => $config['description'],
                ]
            );

            $role->permissions()->sync(
                $permissionCollection->only($config['permissions'])->pluck('id')
            );
        }
    }
}
