<?php

namespace App\Providers;

use App\Services\TipoCambioService;
use Illuminate\Support\ServiceProvider;

class TipoCambioServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TipoCambioService::class, function ($app) {
            return new TipoCambioService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
