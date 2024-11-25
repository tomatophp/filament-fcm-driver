<?php

namespace TomatoPHP\FilamentFcmDriver\Tests;

use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use TomatoPHP\FilamentFcmDriver\Services\FcmMobileDriver;
use TomatoPHP\FilamentFcmDriver\Services\FcmWebDriver;
use TomatoPHP\FilamentFcmDriver\Tests\Models\User;
use TomatoPHP\FilamentFcmDriver\Tests\Models\UserToken;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can send notification using Filament Native Notification Web', function () {
    $user = User::factory()->create();
    UserToken::query()->create([
        'model_type' => get_class($user),
        'model_id' => $user->id,
        'provider' => 'fcm-web',
        'provider_token' => Str::random(12),
    ]);

    Notification::make()
        ->title('Test title')
        ->body('Test body')
        ->icon('heroicon-o-bell')
        ->info()
        ->sendUse($user, FcmWebDriver::class);

    assertDatabaseHas('notifications_logs', [
        'title' => 'Test title',
        'description' => 'Test body',
        'provider' => 'fcm-web',
        'type' => 'info',
    ]);

});

it('can send notification using Filament Native Notification Mobile', function () {
    $user = User::factory()->create();
    UserToken::query()->create([
        'model_type' => get_class($user),
        'model_id' => $user->id,
        'provider' => 'fcm-mobile',
        'provider_token' => Str::random(12),
    ]);

    Notification::make()
        ->title('Test title')
        ->body('Test body')
        ->icon('heroicon-o-bell')
        ->info()
        ->sendUse($user, FcmMobileDriver::class);

    assertDatabaseHas('notifications_logs', [
        'title' => 'Test title',
        'description' => 'Test body',
        'provider' => 'fcm-mobile',
        'type' => 'info',
    ]);

});
