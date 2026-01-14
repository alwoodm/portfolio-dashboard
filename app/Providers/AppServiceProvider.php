<?php

namespace App\Providers;

use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $icons->add('icon', [
            'path' => resource_path('svg'),
            'prefix' => 'icon',
        ]);
    }
}
