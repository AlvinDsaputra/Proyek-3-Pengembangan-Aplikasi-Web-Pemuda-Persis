<?php

namespace Tests\Unit\SharedCore;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\SharedCore\Models\OrganizationalPeriod;
use Modules\SharedCore\Models\Permission;
use Modules\SharedCore\Models\Role;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    private function attachRole(User $user, string $roleSlug, ?OrganizationalPeriod $period = null): Role
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();

        $user->roles()->attach($role->id, [
            'period_id'   => $period?->id,
            'assigned_by' => $user->id,
            'assigned_at' => now(),
        ]);

        return $role;
    }

    public function test_role_permission_seeder_is_idempotent_with_39_permissions_and_no_dokumen_verify(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->assertSame(39, Permission::count());
        $this->assertSame(5, Role::count());
        $this->assertFalse(Permission::where('slug', 'dokumen.verify')->exists());

        $approvalLogPermission = Permission::where('slug', 'surat.approval-log.view')->first();
        $this->assertNotNull($approvalLogPermission);
        $this->assertSame('SuratKeluar', $approvalLogPermission->module);
    }

    public function test_each_role_has_exact_permission_matrix_and_negative_boundaries(): void
    {
        $expectedMatrix = [
            'superadmin' => [
                'shared.users.view',
                'shared.users.create',
                'shared.users.edit',
                'shared.users.toggle-active',
                'shared.org-profile.view',
                'shared.org-profile.edit',
                'shared.periods.manage',
                'surat.approval-log.view',
            ],
            'ketua' => [
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
            'sekretaris' => [
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
            'bidang_administrasi' => [
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
            'anggota' => [
                'dokumen.view',
                'kegiatan.view',
                'kegiatan.register',
            ],
        ];

        foreach ($expectedMatrix as $slug => $expectedPermissions) {
            $role = Role::where('slug', $slug)->firstOrFail();
            $actualPermissions = $role->permissions()->pluck('slug')->sort()->values()->all();
            $sortedExpected = collect($expectedPermissions)->sort()->values()->all();

            $this->assertSame(
                $sortedExpected,
                $actualPermissions,
                "Permission matrix mismatch for role {$slug}."
            );
        }

        $signatureRoles = Role::whereHas('permissions', fn ($q) => $q->where('slug', 'shared.signature.upload'))
            ->pluck('slug')
            ->sort()
            ->values()
            ->all();

        $this->assertSame(['ketua', 'sekretaris'], $signatureRoles);

        $superadmin = User::factory()->create();
        $this->attachRole($superadmin, 'superadmin');
        foreach ([
            'shared.signature.upload',
            'surat.view',
            'surat.draft.create',
            'surat.approval.approve',
            'surat.jenis.manage',
            'dokumen.view',
            'aset.view',
            'kegiatan.view',
        ] as $forbiddenPermission) {
            $this->assertFalse($superadmin->hasPermission($forbiddenPermission));
        }

        $bidangAdmin = User::factory()->create();
        $this->attachRole($bidangAdmin, 'bidang_administrasi');
        $this->assertFalse($bidangAdmin->hasPermission('shared.signature.upload'));
        $this->assertFalse($bidangAdmin->hasPermission('surat.view'));

        $anggota = User::factory()->create();
        $this->attachRole($anggota, 'anggota');
        $this->assertFalse($anggota->hasPermission('surat.view'));
        $this->assertFalse($anggota->hasPermission('shared.signature.upload'));
    }

    public function test_user_has_role_has_permission_primary_role_and_dashboard_route_name(): void
    {
        $expectedRoutes = [
            'superadmin'          => 'admin.dashboard',
            'ketua'               => 'ketua.dashboard',
            'sekretaris'          => 'sekretaris.dashboard',
            'bidang_administrasi' => 'bidang-administrasi.dashboard',
            'anggota'             => 'anggota.dashboard',
        ];

        foreach ($expectedRoutes as $slug => $routeName) {
            $user = User::factory()->create();
            $this->attachRole($user, $slug);

            $this->assertTrue($user->hasRole($slug));
            $this->assertTrue($user->hasRole('nonexistent', $slug));
            $this->assertFalse($user->hasRole());
            $this->assertSame($slug, $user->primaryRole()?->slug);
            $this->assertSame($routeName, $user->dashboardRouteName());
        }

        $unassigned = User::factory()->create();
        $this->assertFalse($unassigned->hasRole('anggota'));
        $this->assertFalse($unassigned->hasPermission('dokumen.view'));
        $this->assertNull($unassigned->primaryRole());
        $this->assertSame('dashboard', $unassigned->dashboardRouteName());
    }

    public function test_primary_role_follows_stable_priority_when_user_has_multiple_roles(): void
    {
        $user = User::factory()->create();
        $this->attachRole($user, 'anggota');
        $this->attachRole($user, 'bidang_administrasi');
        $this->attachRole($user, 'sekretaris');

        $this->assertSame('sekretaris', $user->primaryRole()?->slug);
        $this->assertSame('sekretaris.dashboard', $user->dashboardRouteName());
        $this->assertTrue($user->hasRole('anggota', 'ketua'));
        $this->assertTrue($user->hasPermission('surat.jenis.manage'));
        $this->assertTrue($user->hasPermission('shared.signature.upload'));

        $this->attachRole($user, 'ketua');
        $this->assertSame('ketua', $user->primaryRole()?->slug);
        $this->assertSame('ketua.dashboard', $user->dashboardRouteName());

        $this->attachRole($user, 'superadmin');
        $this->assertSame('superadmin', $user->primaryRole()?->slug);
        $this->assertSame('admin.dashboard', $user->dashboardRouteName());
    }

    public function test_active_roles_handles_missing_active_period_without_error(): void
    {
        $this->assertNull(OrganizationalPeriod::active());

        $inactivePeriod = OrganizationalPeriod::create([
            'name'       => '2025-2027',
            'start_date' => '2025-01-01',
            'end_date'   => '2027-12-31',
            'is_active'  => false,
        ]);

        $permanentUser = User::factory()->create();
        $this->attachRole($permanentUser, 'superadmin', null);

        $periodBoundUser = User::factory()->create();
        $this->attachRole($periodBoundUser, 'ketua', $inactivePeriod);

        $this->assertCount(1, $permanentUser->activeRoles()->get());
        $this->assertTrue($permanentUser->hasRole('superadmin'));
        $this->assertTrue($permanentUser->hasPermission('shared.periods.manage'));

        $this->assertCount(0, $periodBoundUser->activeRoles()->get());
        $this->assertFalse($periodBoundUser->hasRole('ketua'));
        $this->assertFalse($periodBoundUser->hasPermission('shared.signature.upload'));
        $this->assertNull($periodBoundUser->primaryRole());
        $this->assertSame('dashboard', $periodBoundUser->dashboardRouteName());
    }

    public function test_period_scoped_roles_activate_and_expire_with_active_period_while_permanent_roles_persist(): void
    {
        $admin = User::factory()->create();
        $this->attachRole($admin, 'superadmin', null);

        $periodOne = OrganizationalPeriod::create([
            'name'       => '2023-2025',
            'start_date' => '2023-01-01',
            'end_date'   => '2025-12-31',
            'is_active'  => false,
        ]);

        $periodTwo = OrganizationalPeriod::create([
            'name'       => '2025-2027',
            'start_date' => '2025-01-01',
            'end_date'   => '2027-12-31',
            'is_active'  => false,
        ]);

        $officer = User::factory()->create();
        $this->attachRole($officer, 'ketua', $periodOne);
        $this->attachRole($officer, 'sekretaris', $periodTwo);

        $this->assertFalse($officer->hasRole('ketua'));
        $this->assertFalse($officer->hasRole('sekretaris'));
        $this->assertFalse($officer->hasPermission('surat.approval.approve'));
        $this->assertFalse($officer->hasPermission('surat.draft.create'));

        $periodOne->activate($admin);

        $this->assertTrue($officer->hasRole('ketua'));
        $this->assertFalse($officer->hasRole('sekretaris'));
        $this->assertTrue($officer->hasPermission('surat.approval.approve'));
        $this->assertFalse($officer->hasPermission('surat.draft.create'));
        $this->assertSame('ketua', $officer->primaryRole()?->slug);
        $this->assertTrue($admin->hasRole('superadmin'));

        $periodTwo->activate($admin);

        $this->assertFalse($officer->hasRole('ketua'));
        $this->assertTrue($officer->hasRole('sekretaris'));
        $this->assertFalse($officer->hasPermission('surat.approval.approve'));
        $this->assertTrue($officer->hasPermission('surat.draft.create'));
        $this->assertSame('sekretaris', $officer->primaryRole()?->slug);
        $this->assertTrue($admin->hasRole('superadmin'));
        $this->assertTrue($admin->hasPermission('shared.periods.manage'));
    }
}
