<?php

namespace App\Providers;

use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(IconFactory $icons): void
    {
        $icons->add('icon', [
            'path' => resource_path('svg'),
            'prefix' => 'icon',
        ]);
    }
}
