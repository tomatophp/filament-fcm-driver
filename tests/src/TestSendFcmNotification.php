<?php

namespace TomatoPHP\FilamentFcmDriver\Tests;

use Illuminate\Support\Str;
use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentFcmDriver\Services\FcmWebDriver;
use TomatoPHP\FilamentFcmDriver\Services\FcmMobileDriver;
use TomatoPHP\FilamentFcmDriver\Tests\Models\NotificationsTemplate;
use TomatoPHP\FilamentFcmDriver\Tests\Models\User;

use TomatoPHP\FilamentFcmDriver\Tests\Models\UserToken;
use function Pest\Laravel\assertDatabaseHas;

it('can use FilamentAlerts Facade To Notify User FCM Message', function () {
    $user = User::factory()->create();
    $template = NotificationsTemplate::factory()->create();
    UserToken::query()->create([
        "model_type" => get_class($user),
        "model_id" => $user->id,
        "provider" => 'fcm-web',
        "provider_token" => Str::random(12),
    ]);

    UserToken::query()->create([
        "model_type" => get_class($user),
        "model_id" => $user->id,
        "provider" => 'fcm-mobile',
        "provider_token" => Str::random(12),
    ]);

    FilamentAlerts::notify($user)
        ->template($template->id)
        ->drivers([FcmWebDriver::class, FcmMobileDriver::class])
        ->title([
            'name' => $user->name,
        ])
        ->body([
            'date' => now()->toDateTimeString(),
        ])
        ->send();

    assertDatabaseHas('notifications_logs', [
        'title' => $template->title,
        'description' => $template->body,
        'provider' => 'fcm-web',
        'type' => 'info',
    ]);

    assertDatabaseHas('notifications_logs', [
        'title' => $template->title,
        'description' => $template->body,
        'provider' => 'fcm-mobile',
        'type' => 'info',
    ]);
});
