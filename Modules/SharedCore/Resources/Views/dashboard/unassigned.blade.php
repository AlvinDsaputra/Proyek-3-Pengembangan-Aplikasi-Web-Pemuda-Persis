<x-app-layout>
    <x-slot name="header">Beranda</x-slot>

    <div class="mx-auto max-w-4xl space-y-6 pb-8">
        @include('shared-core::dashboard.partials.welcome-banner', [
            'user' => $user,
            'userRole' => $userRole,
            'activePeriod' => $activePeriod,
            'roleFocus' => 'Akun Anda aktif, namun belum terhubung ke peran kepengurusan pada periode berjalan.',
        ])

        <section class="rounded-xl border border-amber-700 bg-amber-50 p-5 sm:p-6">
            <h2 class="text-base font-semibold text-amber-900">Peran Akun Belum Ditetapkan</h2>
            <p class="mt-2 text-sm leading-relaxed text-amber-800">
                Akun Anda belum memiliki peran aktif pada periode kepengurusan saat ini, sehingga menu dan halaman modul organisasi belum dapat ditampilkan. Hubungi Super Admin PD Pemuda Persis Kab. Bandung untuk menetapkan peran akun Anda.
            </p>

            <div class="mt-5">
                <a
                    href="{{ route('profile.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-800 active:bg-green-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
                >
                    Buka Profil Saya
                </a>
            </div>
        </section>
    </div>
</x-app-layout>
