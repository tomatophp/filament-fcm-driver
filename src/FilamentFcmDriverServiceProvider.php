<?php

namespace TomatoPHP\FilamentFcmDriver;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TomatoPHP\FilamentFcmDriver\Livewire\Firebase;


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

        Livewire::component('filament-fcm-driver', Firebase::class);

    }

    public function boot(): void
    {
        try {
            Config::set('filament-fcm-driver.project.apiKey', setting('fcm_project_apiKey'));
            Config::set('filament-fcm-driver.project.authDomain', setting('fcm_project_authDomain'));
            Config::set('filament-fcm-driver.project.databaseURL', setting('fcm_project_databaseURL'));
            Config::set('filament-fcm-driver.project.projectId', setting('fcm_project_projectId'));
            Config::set('filament-fcm-driver.project.storageBucket', setting('fcm_project_storageBucket'));
            Config::set('filament-fcm-driver.project.messagingSenderId', setting('fcm_project_messagingSenderId'));
            Config::set('filament-fcm-driver.project.appId', setting('fcm_project_appId'));
            Config::set('filament-fcm-driver.project.measurementId', setting('fcm_project_measurementId'));
            Config::set('filament-fcm-driver.vapid', setting('fcm_vapid'));
            Config::set('filament-fcm-driver.alert.sound', setting('fcm_alert_sound'));
            Config::set('firebase.projects.app.credentials', storage_path('/app/public/' . setting('fcm_credentials')));
            Config::set('firebase.projects.app.database.url', setting('fcm_project_databaseURL'));
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
