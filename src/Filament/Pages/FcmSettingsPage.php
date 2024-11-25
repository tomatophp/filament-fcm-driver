<?php

namespace TomatoPHP\FilamentFcmDriver\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\Artisan;
use TomatoPHP\FilamentDiscordDriver\Settings\DiscordSettings;
use TomatoPHP\FilamentFcmDriver\Settings\FcmSettings;
use TomatoPHP\FilamentSettingsHub\Pages\SettingsHub;

class FcmSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = FcmSettings::class;

    public function getTitle(): string
    {
        return trans('filament-fcm-driver::messages.settings.fcm.title');
    }

    protected function getActions(): array
    {
        return [
            Action::make('back')->url(SettingsHub::getUrl()),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1])->schema([
                Toggle::make('fcm_active')
                    ->live()
                    ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_active'))
                    ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_active")': null),
                Section::make(trans('filament-fcm-driver::messages.settings.fcm.google_settings'))
                    ->visible(fn(Get $get)=> $get('fcm_active'))
                    ->schema([
                        FileUpload::make('fcm_credentials')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_credentials'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_credentials")': null)
                    ]),
                Section::make(trans('filament-fcm-driver::messages.settings.fcm.project_data'))
                    ->visible(fn(Get $get)=> $get('fcm_active'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('fcm_project_apiKey')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_apiKey'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_apiKey")': null),
                        TextInput::make('fcm_project_authDomain')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_authDomain'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_authDomain")': null),
                        TextInput::make('fcm_project_databaseURL')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_databaseURL'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_databaseURL")': null),
                        TextInput::make('fcm_project_projectId')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_projectId'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_projectId")': null),
                        TextInput::make('fcm_project_storageBucket')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_storageBucket'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_storageBucket")': null),
                        TextInput::make('fcm_project_messagingSenderId')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_messagingSenderId'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_messagingSenderId")': null),
                        TextInput::make('fcm_project_appId')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_appId'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_appId")': null),
                        TextInput::make('fcm_project_measurementId')
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_project_measurementId'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_project_measurementId")': null),
                        Textarea::make('fcm_vapid')
                            ->columnSpanFull()
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_vapid'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_vapid")': null),
                        FileUpload::make('fcm_alert_sound')
                            ->columnSpanFull()
                            ->label(trans('filament-fcm-driver::messages.settings.fcm.fcm_alert_sound'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("fcm_alert_sound")': null)
                    ])
            ]),
        ];
    }

    public function afterSave(): void
    {
        Artisan::call('cache:clear');
    }
}
