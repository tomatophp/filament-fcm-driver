<?php

namespace TomatoPHP\FilamentFcmDriver;

use Illuminate\Support\ServiceProvider;


class FilamentFcmDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentFcmDriver\Console\FilamentFcmDriverInstall::class,
        ]);
 
        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-fcm-driver.php', 'filament-fcm-driver');
 
        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-fcm-driver.php' => config_path('filament-fcm-driver.php'),
        ], 'filament-fcm-driver-config');
 
        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
 
        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-fcm-driver-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-fcm-driver');
 
        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-fcm-driver'),
        ], 'filament-fcm-driver-views');
 
        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-fcm-driver');
 
        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-fcm-driver'),
        ], 'filament-fcm-driver-lang');
 
        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
 
    }

    public function boot(): void
    {
        //you boot methods here
    }
}
