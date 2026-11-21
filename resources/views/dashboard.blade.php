<x-app-layout>
    <x-slot name="header">Beranda</x-slot>

    @php
        $user     = Auth::user();
        $userRole = $user->roles()->first();
        $slug     = $userRole?->slug ?? '';
        $period   = \Modules\SharedCore\Models\OrganizationalPeriod::where('is_active', true)->first();
    @endphp

    {{-- Banner selamat datang --}}
    <div class="bg-green-700 rounded-xl px-6 py-5 text-white mb-6">
        @if($period)
            <p class="text-green-200 text-xs uppercase tracking-widest font-medium mb-1">
                {{ strtoupper($period->name) }}
            </p>
        @endif
        <h2 class="text-xl font-bold">Assalamu'alaikum, {{ $user->name }}</h2>
        <p class="text-green-200 text-sm mt-0.5">
            Anda masuk sebagai <span class="font-semibold text-white">{{ $userRole?->name ?? 'Pengguna' }}</span>
            &mdash; PD Pemuda Persis Kab. Bandung
        </p>
    </div>

    {{-- ============================================================
         SUPERADMIN
    ============================================================ --}}
    @if($slug === 'superadmin')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total Pengguna"  value="—" color="blue"   note="Belum tersedia" />
        <x-stat-card label="Pengguna Aktif"  value="—" color="green"  note="Belum tersedia" />
        <x-stat-card label="Periode Aktif"   value="{{ $period ? 1 : 0 }}" color="yellow" note="{{ $period?->name ?? '-' }}" />
        <x-stat-card label="Total Role"      value="5" color="gray"   note="Terkonfigurasi" />
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Akses Cepat</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.periods.index') }}"
               class="inline-flex items-center gap-2 text-sm text-green-700 hover:text-green-900 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Kelola Periode
            </a>
            <span class="inline-flex items-center gap-2 text-sm text-gray-400 font-medium cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Kelola Pengguna <span class="text-xs">(segera)</span>
            </span>
        </div>
    </div>

    {{-- ============================================================
         KETUA
    ============================================================ --}}
    @elseif($slug === 'ketua')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Surat Menunggu Persetujuan"  value="—" color="yellow" note="Data belum tersedia" />
        <x-stat-card label="Dokumen Belum Diverifikasi"  value="—" color="orange" note="Data belum tersedia" />
        <x-stat-card label="Kegiatan Menunggu"           value="—" color="blue"   note="Data belum tersedia" />
        <x-stat-card label="Total Surat Terbit"          value="—" color="green"  note="Data belum tersedia" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Surat Menunggu Persetujuan</h3>
                <span class="text-xs text-gray-400">Segera tersedia</span>
            </div>
            <p class="text-sm text-gray-400 italic">Data akan muncul setelah modul Surat Keluar selesai diimplementasi.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Kelengkapan Tanda Tangan</h3>
                <span class="text-xs text-gray-400">Segera tersedia</span>
            </div>
            <p class="text-sm text-gray-400 italic">Data akan muncul setelah modul Dokumen &amp; Aset selesai diimplementasi.</p>
        </div>
    </div>

    {{-- ============================================================
         SEKRETARIS
    ============================================================ --}}
    @elseif($slug === 'sekretaris')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Surat Draft"           value="—" color="gray"   note="Data belum tersedia" />
        <x-stat-card label="Surat Diajukan"        value="—" color="yellow" note="Data belum tersedia" />
        <x-stat-card label="Surat Masuk Bulan Ini" value="—" color="blue"   note="Data belum tersedia" />
        <x-stat-card label="Total Surat Dibuat"    value="—" color="green"  note="Data belum tersedia" />
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Aktivitas Surat Terbaru</h3>
            <span class="text-xs text-gray-400">Segera tersedia</span>
        </div>
        <p class="text-sm text-gray-400 italic">Data akan muncul setelah modul Surat Keluar selesai diimplementasi.</p>
    </div>

    {{-- ============================================================
         BIDANG ADMINISTRASI
    ============================================================ --}}
    @elseif($slug === 'bidang_administrasi')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total Dokumen"  value="—" color="blue"   note="Data belum tersedia" />
        <x-stat-card label="Total Aset"     value="—" color="green"  note="Data belum tersedia" />
        <x-stat-card label="Jenis Surat"    value="—" color="yellow" note="Data belum tersedia" />
        <x-stat-card label="Template Surat" value="—" color="gray"   note="Data belum tersedia" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Dokumen Terbaru</h3>
                <span class="text-xs text-gray-400">Segera tersedia</span>
            </div>
            <p class="text-sm text-gray-400 italic">Data akan muncul setelah modul Dokumen &amp; Aset selesai diimplementasi.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Aset Terbaru</h3>
                <span class="text-xs text-gray-400">Segera tersedia</span>
            </div>
            <p class="text-sm text-gray-400 italic">Data akan muncul setelah modul Dokumen &amp; Aset selesai diimplementasi.</p>
        </div>
    </div>

    {{-- ============================================================
         ANGGOTA
    ============================================================ --}}
    @elseif($slug === 'anggota')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Kegiatan Mendatang" value="—" color="blue"   note="Data belum tersedia" />
        <x-stat-card label="Kegiatan Diikuti"   value="—" color="green"  note="Data belum tersedia" />
        <x-stat-card label="Surat Terbit"       value="—" color="gray"   note="Data belum tersedia" />
        <x-stat-card label="Dokumen Tersedia"   value="—" color="yellow" note="Data belum tersedia" />
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Agenda Kegiatan Mendatang</h3>
            <span class="text-xs text-gray-400">Segera tersedia</span>
        </div>
        <p class="text-sm text-gray-400 italic">Data akan muncul setelah modul Kegiatan selesai diimplementasi.</p>
    </div>

    @endif

</x-app-layout>
