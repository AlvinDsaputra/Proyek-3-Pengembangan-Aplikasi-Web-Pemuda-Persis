<?php

namespace Modules\Kegiatan\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Kegiatan\Models\Activity;
use Modules\Kegiatan\Models\Department;

class PublicActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activities = Activity::query()
            ->published()
            ->with('department')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('speaker_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('department'), function ($query) use ($request) {
                $query->where('department_id', $request->integer('department'));
            })
            ->orderBy('start_datetime')
            ->paginate(9)
            ->withQueryString();

        $departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('kegiatan.index', compact('activities', 'departments'));
    }

    public function show(int $id): View
    {
        $activity = Activity::query()
            ->published()
            ->with('department')
            ->findOrFail($id);

        return view('kegiatan.show', compact('activity'));
    }
}
