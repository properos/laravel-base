<?php

namespace Properos\Base;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->version() < "5.8") {
            $this->publishes([
                __DIR__ . '/public/be' => public_path('/be'),
                __DIR__ . '/public/fe' => public_path('/themes'),
                __DIR__ . '/public/.well-known' => public_path('/.well-known'),
                __DIR__ . '/resources/views' => resource_path('views'),
                __DIR__ . '/resources/assets/js/be' => resource_path('assets/js/be'),
                __DIR__ . '/resources/assets/js/global' => resource_path('assets/js'),
            ], 'base');
        } else {
            $this->publishes([
                __DIR__ . '/public/be' => public_path('/be'),
                __DIR__ . '/public/fe' => public_path('/themes'),
                __DIR__ . '/public/.well-known' => public_path('/.well-known'),
                __DIR__ . '/resources/views' => resource_path('views'),
                __DIR__ . '/resources/assets/js/be' => resource_path('js/be'),
                __DIR__ . '/resources/assets/js/global' => resource_path('js'),
            ], 'base');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Properos\Base\Controllers\ApiController');
    }
}
