<?php

namespace Modules\SharedCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\SharedCore\Models\OrganizationalPeriod;

class OrganizationalPeriodController extends Controller
{
    public function index(): View
    {
        $periods = OrganizationalPeriod::latest()->get();

        return view('shared-core::periods.index', compact('periods'));
    }

    public function create(): View
    {
        return view('shared-core::periods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ]);

        OrganizationalPeriod::create($data);

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode kepengurusan berhasil ditambahkan.');
    }

    public function activate(Request $request, OrganizationalPeriod $period): RedirectResponse
    {
        $period->activate($request->user());

        return redirect()->route('admin.periods.index')
            ->with('success', "Periode \"{$period->name}\" diaktifkan.");
    }
}
