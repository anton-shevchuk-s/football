<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
        $this->app->bind('superApiConnector', function ($app) {
            return new ApiConnector($app['config']->get('super-api.key'), $app['config']->get('super-api.secret'));
        });*/
    }
}
