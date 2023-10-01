<?php

namespace Hnllyrp\Adminer;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AdminerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerPublishing();

        if (!config('adminer.enabled')) {
            return;
        }

        // except CSRF verification.
        $router->middlewareGroup('adminer', [
            // \App\Http\Middleware\EncryptCookies::class,
            // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $this->registerRoutes();

    }

    protected function registerRoutes()
    {
        $routeConfiguration = [
            'domain' => config('adminer.domain', null),
            'namespace' => 'Hnllyrp\Adminer\Http\Controllers',
            'prefix' => config('adminer.route_prefix', 'adminer'),
            'middleware' => 'adminer',
        ];

        Route::group($routeConfiguration, function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
    }

    protected function registerPublishing()
    {
        // php artisan vendor:publish --provider="Hnllyrp\Adminer\AdminerServiceProvider" --tag=adminer --force
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/adminer'),
                __DIR__ . '/../config/adminer.php' => config_path('adminer.php'),
            ], 'adminer');
        }
    }

    public function register()
    {
    }
}
