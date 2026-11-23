@include('shared-core::dashboard.unassigned', [
    'user' => $user ?? Auth::user(),
    'userRole' => $userRole ?? Auth::user()?->primaryRole(),
    'activePeriod' => $activePeriod ?? null,
])
