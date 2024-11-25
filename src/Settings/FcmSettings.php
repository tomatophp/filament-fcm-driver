<?php

namespace TomatoPHP\FilamentFcmDriver\Settings;

use Spatie\LaravelSettings\Settings;

class FcmSettings extends Settings
{
    public ?string $fcm_project_apiKey = null;
    public ?string $fcm_project_authDomain = null;
    public ?string $fcm_project_databaseURL = null;
    public ?string $fcm_project_projectId = null;
    public ?string $fcm_project_storageBucket = null;
    public ?string $fcm_project_messagingSenderId = null;
    public ?string $fcm_project_appId = null;
    public ?string $fcm_project_measurementId = null;
    public ?string $fcm_vapid = null;

    public ?string $fcm_credentials = null;
    public ?string $fcm_alert_sound = null;

    public ?bool $fcm_active = true;

    public static function group(): string
    {
        return 'fcm';
    }
}
