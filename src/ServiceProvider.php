<?php

namespace Microit\LaravelAdminRetailTelecoms;

use DCN\RBAC\Models\Permission;
use DCN\RBAC\Models\Role;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Microit\LaravelAdminRetailTelecoms\Commands\RegisterPermissions;
use Microit\LaravelAdminRetailTelecoms\Commands\ResizeImages;


class ServiceProvider extends LaravelServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Routes
         */
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../routes.php';
        }

        /**
         * Views
         */
        $this->loadViewsFrom(__DIR__.'/../views', 'laravel-admin-retail-telecoms');

        /**
         * Migrations
         */
        $this->loadMigrationsFrom( __DIR__.'/../migrations/');

        /**
         * Config
         */
        $this->publishes([ __DIR__ .'/../config/laravel-admin-retail-telecoms.php' => config_path('laravel-admin-retail-telecoms.php')],'config');

        /**
         * Lang
         */
        $this->loadTranslationsFrom( __DIR__ .'/../lang', 'laravel-admin-retail-telecoms');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        /**
         * Commands
         */
        $this->commands(RegisterPermissions::class);
        $this->commands(ResizeImages::class);
    }

    public function provides()
    {
    }
}