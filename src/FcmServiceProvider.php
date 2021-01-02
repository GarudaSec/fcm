<?php

namespace Bratasena\Fcm;

use Bratasena\Fcm\Fcm;
use Illuminate\Support\ServiceProvider;

class FcmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/config/laravel-fcm.php' => config_path('laravel-fcm.php'),
        ]);
    }
    
    public function register()
    {
        $this->app->bind('fcm', function ($app) {
            return new Fcm(
                config('laravel-fcm.server_key')
            );
        });
    }
}
