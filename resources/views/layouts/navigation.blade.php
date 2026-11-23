<aside
    id="app-sidebar"
    aria-label="Navigasi utama"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-40 flex h-full w-64 max-w-[85vw] shrink-0 flex-col overflow-y-auto bg-green-800 transition-transform duration-200 ease-out lg:static lg:translate-x-0"
>
    @include('layouts.partials.sidebar-brand')
    @include('layouts.partials.sidebar-menu')
</aside>
