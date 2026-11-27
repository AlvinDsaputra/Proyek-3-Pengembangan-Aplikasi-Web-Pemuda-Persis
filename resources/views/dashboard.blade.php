@php
    $user = Auth::user();
    $userRole = $user?->roles()->first();
    $slug = $userRole?->slug ?? '';
    $activePeriod = \Modules\SharedCore\Models\OrganizationalPeriod::active();

    $pageTitle = match ($slug) {
        'superadmin' => 'Beranda Admin',
        'ketua' => 'Beranda Ketua PD',
        'sekretaris' => 'Beranda Sekretaris',
        'bidang_administrasi' => 'Beranda Bidang Administrasi',
        'anggota' => 'Beranda Anggota',
        default => 'Beranda',
    };

    $viewName = match ($slug) {
        'superadmin' => 'shared-core::dashboard.admin',
        'ketua' => 'shared-core::dashboard.ketua',
        'sekretaris' => 'shared-core::dashboard.sekretaris',
        'bidang_administrasi' => 'shared-core::dashboard.bidang-administrasi',
        'anggota' => 'shared-core::dashboard.anggota',
        default => 'shared-core::dashboard.unassigned',
    };

    $viewData = [
        'user' => $user,
        'userRole' => $userRole,
        'activePeriod' => $activePeriod,
    ];

    if ($slug === 'superadmin') {
        $userCounts = \Illuminate\Support\Facades\DB::table('users')
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_total')
            ->first();

        $totalUsers = (int) ($userCounts->total ?? 0);
        $activeUsers = (int) ($userCounts->active_total ?? 0);

        $roles = \Modules\SharedCore\Models\Role::all()->keyBy('slug');
        $roleCounts = \Illuminate\Support\Facades\DB::table('user_roles')
            ->select('role_id', \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT user_id) as total'))
            ->groupBy('role_id')
            ->pluck('total', 'role_id');

        $roleSummaries = [];
        foreach (['superadmin', 'ketua', 'sekretaris', 'bidang_administrasi', 'anggota'] as $roleSlug) {
            $role = $roles->get($roleSlug);
            if ($role) {
                $roleSummaries[] = [
                    'slug' => $role->slug,
                    'name' => $role->name,
                    'usersCount' => (int) ($roleCounts[$role->id] ?? 0),
                ];
            }
        }

        $orgProfile = \Illuminate\Support\Facades\DB::table('organization_profiles')
            ->select(['id', 'stamp_path'])
            ->first();

        $recentPeriods = \Modules\SharedCore\Models\OrganizationalPeriod::query()
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->limit(5)
            ->get();

        $viewData['stats'] = [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => max(0, $totalUsers - $activeUsers),
            'totalPeriods' => \Modules\SharedCore\Models\OrganizationalPeriod::count(),
            'totalRoles' => $roles->count(),
            'hasOrganizationProfile' => $orgProfile !== null,
            'hasOrganizationStamp' => $orgProfile !== null && filled($orgProfile->stamp_path),
        ];
        $viewData['roleSummaries'] = $roleSummaries;
        $viewData['recentPeriods'] = $recentPeriods;
    } elseif (in_array($slug, ['ketua', 'sekretaris'], true)) {
        $signature = $user->activeSignature()->first();
        $hasSignature = $signature !== null && $signature->existsInStorage();

        $viewData['signature'] = $signature;
        $viewData['hasSignature'] = $hasSignature;
        $viewData['signatureUrl'] = $hasSignature
            ? route('shared.signature.preview', ['v' => $signature->updated_at?->timestamp ?? $signature->id])
            : null;
        $viewData['signatureHistoryCount'] = $user->signatureImages()->count();

        if ($slug === 'sekretaris') {
            $viewData['hasOrganizationStamp'] = \Illuminate\Support\Facades\DB::table('organization_profiles')
                ->whereNotNull('stamp_path')
                ->where('stamp_path', '!=', '')
                ->exists();
        }
    } elseif ($slug === 'bidang_administrasi') {
        $viewData['totalBranches'] = \Illuminate\Support\Facades\DB::table('provincial_branches')->count();
    } elseif ($slug === 'anggota') {
        $viewData['memberNpa'] = $user->npa;
        $viewData['memberBranchName'] = $user->asal_pc_id
            ? \Illuminate\Support\Facades\DB::table('provincial_branches')->where('id', $user->asal_pc_id)->value('name')
            : null;
    }
    \Illuminate\Support\Facades\View::share('userRole', $userRole);
@endphp

<x-app-layout>
    <x-slot name="header">{{ $pageTitle }}</x-slot>

    @include($viewName, $viewData)
</x-app-layout>
