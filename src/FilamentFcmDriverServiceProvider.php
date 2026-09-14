<?php

namespace TomatoPHP\FilamentFcmDriver;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TomatoPHP\FilamentFcmDriver\Console\FilamentFcmDriverInstall;
use TomatoPHP\FilamentFcmDriver\Livewire\Firebase;

class FilamentFcmDriverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register generate command
        $this->commands([
            FilamentFcmDriverInstall::class,
        ]);

        // Register Config file
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-fcm-driver.php', 'filament-fcm-driver');

        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/filament-fcm-driver.php' => config_path('filament-fcm-driver.php'),
        ], 'filament-fcm-driver-config');

        // Register Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'filament-fcm-driver-migrations');
        // Register views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-fcm-driver');

        // Publish Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-fcm-driver'),
        ], 'filament-fcm-driver-views');

        // Register Langs
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-fcm-driver');

        // Publish Lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('lang/vendor/filament-fcm-driver'),
        ], 'filament-fcm-driver-lang');

        // Register Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        Livewire::component('filament-fcm-driver', Firebase::class);

    }

    public function boot(): void
    {
        try {
            // Settings saved from the settings hub win over the env based config, empty settings keep the config value.
            foreach ([
                'filament-fcm-driver.project.apiKey' => 'fcm_project_apiKey',
                'filament-fcm-driver.project.authDomain' => 'fcm_project_authDomain',
                'filament-fcm-driver.project.databaseURL' => 'fcm_project_databaseURL',
                'filament-fcm-driver.project.projectId' => 'fcm_project_projectId',
                'filament-fcm-driver.project.storageBucket' => 'fcm_project_storageBucket',
                'filament-fcm-driver.project.messagingSenderId' => 'fcm_project_messagingSenderId',
                'filament-fcm-driver.project.appId' => 'fcm_project_appId',
                'filament-fcm-driver.project.measurementId' => 'fcm_project_measurementId',
                'filament-fcm-driver.vapid' => 'fcm_vapid',
                'firebase.projects.app.database.url' => 'fcm_project_databaseURL',
            ] as $config => $setting) {
                $value = setting($setting);

                if (filled($value)) {
                    Config::set($config, $value);
                }
            }

            $sound = setting('fcm_alert_sound');
            if (filled($sound)) {
                Config::set('filament-fcm-driver.alert.sound', Storage::disk('public')->url($sound));
            }

            $credentials = setting('fcm_credentials');
            if (filled($credentials)) {
                Config::set('firebase.projects.app.credentials', $this->credentialsPath($credentials));
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    /**
     * The service account key is uploaded to the private local disk; keys uploaded by
     * older versions of the package live on the public disk.
     */
    protected function credentialsPath(string $credentials): string
    {
        $path = Storage::disk('local')->path($credentials);

        return file_exists($path) ? $path : storage_path('app/public/' . $credentials);
    }
}
