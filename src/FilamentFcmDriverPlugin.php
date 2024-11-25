<?php

namespace TomatoPHP\FilamentFcmDriver;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentAlerts\Services\Concerns\NotificationDriver;
use TomatoPHP\FilamentFcmDriver\Filament\Pages\FcmSettingsPage;
use TomatoPHP\FilamentFcmDriver\Services\FcmDriver;
use TomatoPHP\FilamentFcmDriver\Services\FcmMobileDriver;
use TomatoPHP\FilamentFcmDriver\Services\FcmWebDriver;
use TomatoPHP\FilamentSettingsHub\Facades\FilamentSettingsHub;
use TomatoPHP\FilamentSettingsHub\Services\Contracts\SettingHold;

class FilamentFcmDriverPlugin implements Plugin
{

    public function getId(): string
    {
        return 'filament-fcm-driver';
    }

    public function register(Panel $panel): void
    {
        if (class_exists(FilamentSettingsHub::class) && $panel->getPlugin('filament-alerts')->useSettingsHub) {
            $panel->pages([
                FcmSettingsPage::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            function (){
                return view('filament-fcm-driver::firebase');
            },
        );


        if (class_exists(FilamentSettingsHub::class) && filament('filament-alerts')->useSettingsHub) {
            FilamentSettingsHub::register([
                SettingHold::make()
                    ->label('filament-fcm-driver::messages.settings.fcm.title')
                    ->icon('bxl-firebase')
                    ->page(FcmSettingsPage::class)
                    ->order(2)
                    ->description('filament-fcm-driver::messages.settings.fcm.description')
                    ->group('filament-alerts::messages.settings.group'),
            ]);
        }

        FilamentAlerts::register([
            NotificationDriver::make('fcm-mobile')
                ->label('Firebase Mobile')
                ->driver(FcmMobileDriver::class),
            NotificationDriver::make('fcm-web')
                ->label('Firebase Web')
                ->driver(FcmWebDriver::class)
        ]);
    }

    public static function make(): self
    {
        return new FilamentFcmDriverPlugin;
    }
}
