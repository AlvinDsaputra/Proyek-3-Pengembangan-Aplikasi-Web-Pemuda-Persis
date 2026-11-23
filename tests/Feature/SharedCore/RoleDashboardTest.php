<?php

namespace Tests\Feature\SharedCore;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\SharedCore\Models\OrganizationalPeriod;
use Modules\SharedCore\Models\Role;
use Modules\SharedCore\Services\SignatureService;
use Tests\TestCase;

class RoleDashboardTest extends TestCase
{
    use RefreshDatabase;

    private const ROLE_ROUTES = [
        'superadmin' => [
            'route' => 'admin.dashboard',
            'path'  => '/admin/dashboard',
            'view'  => 'shared-core::dashboard.admin',
            'title' => 'Beranda Admin',
        ],
        'ketua' => [
            'route' => 'ketua.dashboard',
            'path'  => '/ketua/dashboard',
            'view'  => 'shared-core::dashboard.ketua',
            'title' => 'Beranda Ketua PD',
        ],
        'sekretaris' => [
            'route' => 'sekretaris.dashboard',
            'path'  => '/sekretaris/dashboard',
            'view'  => 'shared-core::dashboard.sekretaris',
            'title' => 'Beranda Sekretaris',
        ],
        'bidang_administrasi' => [
            'route' => 'bidang-administrasi.dashboard',
            'path'  => '/bidang-administrasi/dashboard',
            'view'  => 'shared-core::dashboard.bidang-administrasi',
            'title' => 'Beranda Bidang Administrasi',
        ],
        'anggota' => [
            'route' => 'anggota.dashboard',
            'path'  => '/anggota/dashboard',
            'view'  => 'shared-core::dashboard.anggota',
            'title' => 'Beranda Anggota',
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        Storage::fake('local');
        Storage::fake('public');
    }

    private function createUserWithRole(
        string $roleSlug,
        array $attributes = [],
        ?OrganizationalPeriod $period = null,
    ): User {
        $user = User::factory()->create($attributes);
        $role = Role::where('slug', $roleSlug)->firstOrFail();

        $user->roles()->attach($role->id, [
            'period_id'   => $period?->id,
            'assigned_by' => $user->id,
            'assigned_at' => now(),
        ]);

        return $user;
    }

    private function extractSidebarHtml(string $html): string
    {
        $matched = preg_match('/<aside\b[^>]*id="app-sidebar"[^>]*>.*?<\/aside>/s', $html, $matches);
        $this->assertSame(1, $matched, 'Sidebar element #app-sidebar not found in response.');

        return $matches[0];
    }

    public function test_guest_is_redirected_to_login_on_all_dashboard_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));

        foreach (self::ROLE_ROUTES as $config) {
            $this->get(route($config['route']))->assertRedirect(route('login'));
        }
    }

    public function test_dashboard_entry_redirects_each_role_to_its_dedicated_dashboard_and_renders_expected_view(): void
    {
        foreach (self::ROLE_ROUTES as $roleSlug => $config) {
            $user = $this->createUserWithRole($roleSlug);

            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertRedirect(route($config['route']));

            $this->actingAs($user)
                ->get($config['path'])
                ->assertOk()
                ->assertViewIs($config['view'])
                ->assertSee($config['title'])
                ->assertSee($user->name);
        }
    }

    public function test_unassigned_user_renders_unassigned_dashboard_without_redirect_loop_and_is_forbidden_from_role_dashboards(): void
    {
        $unassigned = User::factory()->create(['name' => 'Pengguna Baru']);

        $this->actingAs($unassigned)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('shared-core::dashboard.unassigned')
            ->assertSee('Peran Akun Belum Ditetapkan')
            ->assertSee(route('profile.edit'), false);

        foreach (self::ROLE_ROUTES as $config) {
            $this->actingAs($unassigned)
                ->get(route($config['route']))
                ->assertForbidden();
        }
    }

    public function test_user_with_only_inactive_period_role_falls_back_to_unassigned_and_cannot_open_role_dashboard(): void
    {
        $oldPeriod = OrganizationalPeriod::create([
            'name'       => '2021-2023',
            'start_date' => '2021-01-01',
            'end_date'   => '2023-12-31',
            'is_active'  => false,
        ]);

        OrganizationalPeriod::create([
            'name'       => '2025-2027',
            'start_date' => '2025-01-01',
            'end_date'   => '2027-12-31',
            'is_active'  => true,
        ]);

        $formerKetua = $this->createUserWithRole('ketua', [], $oldPeriod);

        $this->actingAs($formerKetua)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('shared-core::dashboard.unassigned');

        $this->actingAs($formerKetua)
            ->get(route('ketua.dashboard'))
            ->assertForbidden();
    }

    public function test_cross_role_dashboard_access_returns_403_for_all_other_roles(): void
    {
        foreach (self::ROLE_ROUTES as $ownRoleSlug => $ownConfig) {
            $user = $this->createUserWithRole($ownRoleSlug);

            foreach (self::ROLE_ROUTES as $targetRoleSlug => $targetConfig) {
                $response = $this->actingAs($user)->get(route($targetConfig['route']));

                if ($ownRoleSlug === $targetRoleSlug) {
                    $response->assertOk();
                } else {
                    $response->assertForbidden();
                }
            }
        }
    }

    public function test_sidebar_menu_matches_permissions_for_every_role(): void
    {
        $expectations = [
            'superadmin' => [
                'route'   => 'admin.dashboard',
                'mustSee' => [
                    'Beranda',
                    'Persuratan',
                    'Log Persetujuan',
                    'Administrasi Sistem',
                    'Periode Kepengurusan',
                    'Kelola Pengguna',
                    'Profil &amp; Cap Organisasi',
                    route('admin.periods.index'),
                ],
                'mustNotSee' => [
                    'Tanda Tangan Saya',
                    route('shared.signature.edit'),
                    'Surat Keluar',
                    'Master Surat',
                    'Surat Masuk',
                    'Dokumen &amp; Arsip',
                    'Aset Organisasi',
                    'Kegiatan',
                ],
                'pageMustNotSee' => ['Tanda Tangan Saya', route('shared.signature.edit')],
            ],
            'ketua' => [
                'route'   => 'ketua.dashboard',
                'mustSee' => [
                    'Beranda',
                    'Persuratan',
                    'Surat Keluar',
                    'Log Persetujuan',
                    'Tanda Tangan Saya',
                    route('shared.signature.edit'),
                    'Dokumen &amp; Arsip',
                    'Aset Organisasi',
                    'Kegiatan',
                ],
                'mustNotSee' => [
                    'Master Surat',
                    'Surat Masuk',
                    'Administrasi Sistem',
                    'Periode Kepengurusan',
                    'Kelola Pengguna',
                ],
                'pageMustNotSee' => ['Administrasi Sistem'],
            ],
            'sekretaris' => [
                'route'   => 'sekretaris.dashboard',
                'mustSee' => [
                    'Beranda',
                    'Persuratan',
                    'Surat Keluar',
                    'Log Persetujuan',
                    'Tanda Tangan Saya',
                    route('shared.signature.edit'),
                    'Surat Masuk',
                    'Dokumen &amp; Arsip',
                    'Aset Organisasi',
                    'Kegiatan',
                ],
                'mustNotSee' => [
                    'Master Surat',
                    'Administrasi Sistem',
                    'Periode Kepengurusan',
                    'Kelola Pengguna',
                ],
                'pageMustNotSee' => ['Administrasi Sistem'],
            ],
            'bidang_administrasi' => [
                'route'   => 'bidang-administrasi.dashboard',
                'mustSee' => [
                    'Beranda',
                    'Persuratan',
                    'Master Surat',
                    'Dokumen &amp; Arsip',
                    'Aset Organisasi',
                    'Kegiatan',
                ],
                'mustNotSee' => [
                    'Tanda Tangan Saya',
                    route('shared.signature.edit'),
                    'Surat Keluar',
                    'Log Persetujuan',
                    'Surat Masuk',
                    'Administrasi Sistem',
                    'Periode Kepengurusan',
                ],
                'pageMustNotSee' => ['Tanda Tangan Saya', route('shared.signature.edit')],
            ],
            'anggota' => [
                'route'   => 'anggota.dashboard',
                'mustSee' => [
                    'Beranda',
                    'Dokumen &amp; Arsip',
                    'Kegiatan',
                ],
                'mustNotSee' => [
                    'Persuratan',
                    'Tanda Tangan Saya',
                    route('shared.signature.edit'),
                    'Surat Keluar',
                    'Master Surat',
                    'Log Persetujuan',
                    'Surat Masuk',
                    'Aset Organisasi',
                    'Administrasi Sistem',
                    'Periode Kepengurusan',
                ],
                'pageMustNotSee' => ['Tanda Tangan Saya', route('shared.signature.edit')],
            ],
        ];

        foreach ($expectations as $roleSlug => $spec) {
            $user = $this->createUserWithRole($roleSlug);
            $response = $this->actingAs($user)->get(route($spec['route']));
            $response->assertOk();

            $fullHtml = $response->getContent();
            $sidebarHtml = $this->extractSidebarHtml($fullHtml);

            foreach ($spec['mustSee'] as $expectedText) {
                $this->assertStringContainsString(
                    $expectedText,
                    $sidebarHtml,
                    "Sidebar for {$roleSlug} should contain '{$expectedText}'."
                );
            }

            foreach ($spec['mustNotSee'] as $forbiddenText) {
                $this->assertStringNotContainsString(
                    $forbiddenText,
                    $sidebarHtml,
                    "Sidebar for {$roleSlug} must not contain '{$forbiddenText}'."
                );
            }

            foreach ($spec['pageMustNotSee'] as $forbiddenPageText) {
                $this->assertStringNotContainsString(
                    $forbiddenPageText,
                    $fullHtml,
                    "Dashboard page for {$roleSlug} must not contain '{$forbiddenPageText}'."
                );
            }
        }

        $unassigned = User::factory()->create();
        $unassignedSidebar = $this->extractSidebarHtml(
            $this->actingAs($unassigned)->get(route('dashboard'))->assertOk()->getContent()
        );

        $this->assertStringContainsString('Beranda', $unassignedSidebar);
        foreach (['Persuratan', 'Tanda Tangan Saya', 'Dokumen &amp; Aset', 'Kegiatan', 'Administrasi Sistem'] as $item) {
            $this->assertStringNotContainsString($item, $unassignedSidebar);
        }
    }

    public function test_admin_dashboard_displays_real_database_metrics(): void
    {
        $admin = $this->createUserWithRole('superadmin', ['is_active' => true]);
        User::factory()->count(2)->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        OrganizationalPeriod::create([
            'name'       => '2023-2025',
            'start_date' => '2023-01-01',
            'end_date'   => '2025-12-31',
            'is_active'  => false,
        ]);

        OrganizationalPeriod::create([
            'name'       => '2025-2027',
            'start_date' => '2025-01-01',
            'end_date'   => '2027-12-31',
            'is_active'  => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('stats', [
                'totalUsers'             => 4,
                'activeUsers'            => 3,
                'inactiveUsers'          => 1,
                'totalPeriods'           => 2,
                'totalRoles'             => 5,
                'hasOrganizationProfile' => false,
                'hasOrganizationStamp'   => false,
            ])
            ->assertSee('1 akun nonaktif')
            ->assertSee('Dari 4 akun terdaftar')
            ->assertSee('Aktif: 2025-2027')
            ->assertSee('Belum diunggah')
            ->assertSee('Profil organisasi belum diatur')
            ->assertSee(route('admin.periods.index'), false);

        DB::table('organization_profiles')->insert([
            'org_name'   => 'PD Pemuda Persis Kab. Bandung',
            'stamp_path' => 'stamps/cap-resmi.png',
            'updated_by' => $admin->id,
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('stats.hasOrganizationProfile', true)
            ->assertViewHas('stats.hasOrganizationStamp', true)
            ->assertSee('Tersedia')
            ->assertSee('Profil organisasi tercatat');
    }

    public function test_ketua_and_sekretaris_dashboards_display_real_signature_status_before_and_after_upload(): void
    {
        $service = app(SignatureService::class);

        foreach (['ketua' => 'ketua.dashboard', 'sekretaris' => 'sekretaris.dashboard'] as $roleSlug => $routeName) {
            $user = $this->createUserWithRole($roleSlug);

            $this->actingAs($user)
                ->get(route($routeName))
                ->assertOk()
                ->assertViewHas('hasSignature', false)
                ->assertViewHas('signatureUrl', null)
                ->assertViewHas('signatureHistoryCount', 0)
                ->assertSee('Belum diunggah')
                ->assertSee('Unggah Tanda Tangan PNG')
                ->assertSee(route('shared.signature.edit'), false);

            $signature = $service->uploadForUser(
                $user,
                UploadedFile::fake()->image("ttd-{$roleSlug}.png", 240, 96)
            );

            $this->actingAs($user)
                ->get(route($routeName))
                ->assertOk()
                ->assertViewHas('hasSignature', true)
                ->assertViewHas('signatureHistoryCount', 1)
                ->assertSee('Tanda tangan aktif')
                ->assertSee('1 berkas')
                ->assertSee('Kelola Tanda Tangan')
                ->assertSee(route('shared.signature.preview'), false)
                ->assertSee(route('shared.signature.edit'), false);

            Storage::disk('local')->delete($signature->file_path);

            $this->actingAs($user)
                ->get(route($routeName))
                ->assertOk()
                ->assertViewHas('hasSignature', false)
                ->assertViewHas('signatureUrl', null)
                ->assertSee('Berkas tidak ditemukan')
                ->assertSee('Unggah Ulang Tanda Tangan PNG');
        }
    }

    public function test_bidang_administrasi_and_anggota_dashboards_display_real_database_context(): void
    {
        $bidangAdmin = $this->createUserWithRole('bidang_administrasi');

        $this->actingAs($bidangAdmin)
            ->get(route('bidang-administrasi.dashboard'))
            ->assertOk()
            ->assertViewHas('totalBranches', 0)
            ->assertSee('Data Pimpinan Cabang belum di-seed ke basis data');

        DB::table('provincial_branches')->insert([
            [
                'name'       => 'PC Pemuda Persis Banjaran',
                'code'       => 'PC-BJR',
                'region'     => 'Banjaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'PC Pemuda Persis Soreang',
                'code'       => 'PC-SRG',
                'region'     => 'Soreang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->actingAs($bidangAdmin)
            ->get(route('bidang-administrasi.dashboard'))
            ->assertOk()
            ->assertViewHas('totalBranches', 2)
            ->assertSee('Tercatat pada basis data cabang organisasi');

        $anggotaWithNpa = $this->createUserWithRole('anggota', ['npa' => 'NPA-2026-009']);
        $this->actingAs($anggotaWithNpa)
            ->get(route('anggota.dashboard'))
            ->assertOk()
            ->assertViewHas('memberNpa', 'NPA-2026-009')
            ->assertSee('NPA-2026-009');

        $anggotaWithoutNpa = $this->createUserWithRole('anggota', ['npa' => null]);
        $this->actingAs($anggotaWithoutNpa)
            ->get(route('anggota.dashboard'))
            ->assertOk()
            ->assertViewHas('memberNpa', null)
            ->assertSee('Belum diisi');
    }
}
