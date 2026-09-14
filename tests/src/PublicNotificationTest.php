<?php

namespace TomatoPHP\FilamentFcmDriver\Tests;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use TomatoPHP\FilamentFcmDriver\Jobs\NotifyFCMJob;
use TomatoPHP\FilamentFcmDriver\Services\FcmMobileDriver;
use TomatoPHP\FilamentFcmDriver\Services\FcmWebDriver;
use TomatoPHP\FilamentFcmDriver\Tests\Models\User;
use TomatoPHP\FilamentFcmDriver\Tests\Models\UserToken;

function giveFcmToken(User $user, string $provider): void
{
    UserToken::query()->create([
        'model_type' => $user::class,
        'model_id' => $user->id,
        'provider' => $provider,
        'provider_token' => Str::random(12),
    ]);
}

beforeEach(fn () => config()->set('filament-alerts.queue', 'alerts'));

it('sends a public notification to every user that has a token', function (string $driver, string $provider) {
    Queue::fake();

    [$first, $second, $withoutToken] = User::factory()->count(3)->create();
    giveFcmToken($first, $provider);
    giveFcmToken($second, $provider);

    app($driver)->sendIt(title: 'Hello everyone', model: User::class, modelId: null, body: 'Public message');

    Queue::assertPushedOn('alerts', NotifyFCMJob::class);
    Queue::assertPushed(NotifyFCMJob::class, 2);
    Queue::assertPushed(NotifyFCMJob::class, fn (NotifyFCMJob $job) => $job->user->is($first));
    Queue::assertPushed(NotifyFCMJob::class, fn (NotifyFCMJob $job) => $job->user->is($second));
    Queue::assertNotPushed(NotifyFCMJob::class, fn (NotifyFCMJob $job) => $job->user->is($withoutToken));
})->with([
    'web' => [FcmWebDriver::class, 'fcm-web'],
    'mobile' => [FcmMobileDriver::class, 'fcm-mobile'],
]);

it('queues a single user notification on the alerts queue', function (string $driver, string $provider) {
    Queue::fake();

    $user = User::factory()->create();
    giveFcmToken($user, $provider);

    app($driver)->sendIt(title: 'Hello', model: User::class, modelId: $user->id, body: 'Private message');

    Queue::assertPushedOn('alerts', NotifyFCMJob::class, fn (NotifyFCMJob $job) => $job->user->is($user));
})->with([
    'web' => [FcmWebDriver::class, 'fcm-web'],
    'mobile' => [FcmMobileDriver::class, 'fcm-mobile'],
]);

it('skips a single user notification when the user does not exist', function (string $driver, string $provider) {
    Queue::fake();

    UserToken::query()->create([
        'model_type' => User::class,
        'model_id' => 999,
        'provider' => $provider,
        'provider_token' => Str::random(12),
    ]);

    app($driver)->sendIt(title: 'Hello', model: User::class, modelId: 999, body: 'Private message');

    Queue::assertNothingPushed();
})->with([
    'web' => [FcmWebDriver::class, 'fcm-web'],
    'mobile' => [FcmMobileDriver::class, 'fcm-mobile'],
]);
