<?php

namespace lucasgiovanny\SerproDataValid;

use Illuminate\Support\ServiceProvider;

class SerproDataValidServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . 'config/serpro-datavalid.php' => config_path('serpro-datavalid.php')
        ], 'serpro-datavalid');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/serpro-datavalid.php',
            'serpro-datavalid'
        );

        $this->app->singleton('SerproDataValid', function () {
            return new SerproDataValid(
                config('serpro-datavalid.consumerKey'),
                config('serpro-datavalid.consumerSecret'),
                config('serpro-datavalid.sandbox')
            );
        });
    }
}
