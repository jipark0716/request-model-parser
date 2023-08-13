<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $configPath = __DIR__.'/../../config/jipark-swagger.php';
        $this->mergeConfigFrom($configPath, 'jipark-swagger');
        $this->app['config']->set('l5-swagger', config('jipark-swagger'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
