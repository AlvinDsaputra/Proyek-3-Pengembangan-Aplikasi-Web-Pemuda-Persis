<?php

namespace Modules\SharedCore\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\SharedCore\Models\OrganizationalPeriod;
use Modules\SharedCore\Models\Role;

class DashboardDataService
{
    public function baseContext(User $user): array
    {
        return [
            'user' => $user,
            'userRole' => $user->primaryRole(),
            'activePeriod' => OrganizationalPeriod::active(),
        ];
    }

    public function forAdmin(User $user): array
    {
        return array_merge($this->baseContext($user), [
            'stats' => [
                'totalUsers' => User::count(),
                'activeUsers' => User::where('is_active', true)->count(),
                'inactiveUsers' => User::where('is_active', false)->count(),
                'totalPeriods' => OrganizationalPeriod::count(),
                'totalRoles' => Role::count(),
                'hasOrganizationProfile' => DB::table('organization_profiles')->exists(),
                'hasOrganizationStamp' => DB::table('organization_profiles')
                    ->whereNotNull('stamp_path')
                    ->where('stamp_path', '!=', '')
                    ->exists(),
            ],
        ]);
    }

    public function forKetua(User $user): array
    {
        return array_merge($this->baseContext($user), $this->signatureContext($user));
    }

    public function forSekretaris(User $user): array
    {
        return array_merge($this->baseContext($user), $this->signatureContext($user));
    }

    public function forBidangAdministrasi(User $user): array
    {
        return array_merge($this->baseContext($user), [
            'totalBranches' => DB::table('provincial_branches')->count(),
        ]);
    }

    public function forAnggota(User $user): array
    {
        return array_merge($this->baseContext($user), [
            'memberNpa' => $user->npa,
        ]);
    }

    private function signatureContext(User $user): array
    {
        $signature = $user->activeSignature()->first();
        $hasSignature = $signature !== null && $signature->existsInStorage();

        return [
            'signature' => $signature,
            'hasSignature' => $hasSignature,
            'signatureUrl' => $hasSignature
                ? route('shared.signature.preview', [
                    'v' => $signature->updated_at?->timestamp ?? $signature->id,
                ])
                : null,
            'signatureHistoryCount' => $user->signatureImages()->count(),
        ];
    }
}
