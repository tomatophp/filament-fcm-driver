<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('fcm.fcm_project_apiKey', '');
        $this->migrator->add('fcm.fcm_project_authDomain', '');
        $this->migrator->add('fcm.fcm_project_databaseURL', '');
        $this->migrator->add('fcm.fcm_project_projectId', '');
        $this->migrator->add('fcm.fcm_project_storageBucket', '');
        $this->migrator->add('fcm.fcm_project_messagingSenderId', '');
        $this->migrator->add('fcm.fcm_project_appId', '');
        $this->migrator->add('fcm.fcm_project_measurementId', '');
        $this->migrator->add('fcm.fcm_vapid', '');
        $this->migrator->add('fcm.fcm_credentials', '');
        $this->migrator->add('fcm.fcm_alert_sound', '');
        $this->migrator->add('fcm.fcm_active', true);
    }
};
