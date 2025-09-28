<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentFcmDriver\FilamentFcmDriverPlugin;

it('registers plugin', function () {
    $panel = Filament::getCurrentOrDefaultPanel();

    $panel->plugins([
        FilamentFcmDriverPlugin::make(),
    ]);

    expect($panel->getPlugin('filament-fcm-driver'))
        ->not()
        ->toThrow(Exception::class);
});
