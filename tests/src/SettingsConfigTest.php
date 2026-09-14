<?php

namespace TomatoPHP\FilamentFcmDriver\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use TomatoPHP\FilamentFcmDriver\FilamentFcmDriverServiceProvider;

function saveFcmSetting(string $name, mixed $value): void
{
    DB::table('settings')->updateOrInsert(
        ['group' => 'fcm', 'name' => $name],
        ['payload' => json_encode($value), 'locked' => false],
    );
}

function bootFcmProvider(): void
{
    (new FilamentFcmDriverServiceProvider(app()))->boot();
}

it('reads the service account key from the private local disk', function () {
    Storage::fake('local');
    Storage::disk('local')->put('fcm/service-account.json', '{}');
    saveFcmSetting('fcm_credentials', 'fcm/service-account.json');

    bootFcmProvider();

    expect(config('firebase.projects.app.credentials'))
        ->toBe(Storage::disk('local')->path('fcm/service-account.json'));
});

it('still finds a service account key uploaded to the public disk by older versions', function () {
    Storage::fake('local');
    saveFcmSetting('fcm_credentials', 'legacy-service-account.json');

    bootFcmProvider();

    expect(config('firebase.projects.app.credentials'))
        ->toBe(storage_path('app/public/legacy-service-account.json'));
});

it('keeps the env firebase config when the settings are empty', function () {
    config()->set('filament-fcm-driver.project.apiKey', 'env-api-key');
    config()->set('filament-fcm-driver.vapid', 'env-vapid');
    saveFcmSetting('fcm_project_apiKey', '');
    saveFcmSetting('fcm_vapid', null);

    bootFcmProvider();

    expect(config('filament-fcm-driver.project.apiKey'))->toBe('env-api-key')
        ->and(config('filament-fcm-driver.vapid'))->toBe('env-vapid');
});

it('uses the firebase settings saved from the settings hub', function () {
    saveFcmSetting('fcm_project_apiKey', 'hub-api-key');
    saveFcmSetting('fcm_alert_sound', 'fcm/ding.mp3');

    bootFcmProvider();

    expect(config('filament-fcm-driver.project.apiKey'))->toBe('hub-api-key')
        ->and(config('filament-fcm-driver.alert.sound'))->toBe(Storage::disk('public')->url('fcm/ding.mp3'));
});
