<?php

namespace Modules\SharedCore\Providers;

use Illuminate\Support\ServiceProvider;

class SharedCoreServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'shared-core');
    }
}