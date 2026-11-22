<?php

namespace Modules\SharedCore\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\SharedCore\Models\SignatureImage;
use Modules\SharedCore\Policies\SignatureImagePolicy;

class SharedCoreServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'shared-core');

        Gate::policy(SignatureImage::class, SignatureImagePolicy::class);
    }
}