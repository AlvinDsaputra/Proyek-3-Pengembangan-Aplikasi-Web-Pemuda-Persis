<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    Modules\SharedCore\Providers\SharedCoreServiceProvider::class,
    Modules\SuratKeluar\Providers\SuratKeluarServiceProvider::class,
    Modules\DokumenAset\Providers\DokumenAsetServiceProvider::class,
    Modules\Kegiatan\Providers\KegiatanServiceProvider::class,
];
