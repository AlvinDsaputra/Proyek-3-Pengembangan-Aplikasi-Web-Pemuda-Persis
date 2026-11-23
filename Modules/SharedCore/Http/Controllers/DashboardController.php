<?php

namespace Modules\SharedCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\SharedCore\Services\DashboardDataService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardDataService $dashboardData,
    ) {}

    public function redirectByRole(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        $routeName = $user->dashboardRouteName();

        if ($routeName !== 'dashboard' && Route::has($routeName)) {
            return redirect()->route($routeName);
        }

        return view('shared-core::dashboard.unassigned', $this->dashboardData->baseContext($user));
    }

    public function admin(Request $request): View
    {
        return view('shared-core::dashboard.admin', $this->dashboardData->forAdmin($request->user()));
    }

    public function ketua(Request $request): View
    {
        return view('shared-core::dashboard.ketua', $this->dashboardData->forKetua($request->user()));
    }

    public function sekretaris(Request $request): View
    {
        return view('shared-core::dashboard.sekretaris', $this->dashboardData->forSekretaris($request->user()));
    }

    public function bidangAdministrasi(Request $request): View
    {
        return view(
            'shared-core::dashboard.bidang-administrasi',
            $this->dashboardData->forBidangAdministrasi($request->user())
        );
    }

    public function anggota(Request $request): View
    {
        return view('shared-core::dashboard.anggota', $this->dashboardData->forAnggota($request->user()));
    }
}
