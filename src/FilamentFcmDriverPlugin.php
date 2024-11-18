<?php

namespace TomatoPHP\FilamentFcmDriver;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentFcmDriverPlugin implements Plugin
{

    public function getId(): string
    {
        return 'filament-fcm-driver';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): self
    {
        return new FilamentFcmDriverPlugin;
    }
}
