<?php

namespace Modules\SuratKeluar\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\SuratKeluar\Models\LetterDestinationCode;
use Modules\SuratKeluar\Models\LetterTemplate;
use Modules\SuratKeluar\Models\LetterType;
use Modules\SuratKeluar\Policies\LetterMasterPolicy;

class SuratKeluarServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void {
        Gate::policy(LetterType::class, LetterMasterPolicy::class);
        Gate::policy(LetterDestinationCode::class, LetterMasterPolicy::class);
        Gate::policy(LetterTemplate::class, LetterMasterPolicy::class);
    }
    
    
}