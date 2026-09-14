<?php

namespace TomatoPHP\FilamentFcmDriver\Tests;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use TomatoPHP\FilamentFcmDriver\Livewire\Firebase;
use TomatoPHP\FilamentFcmDriver\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(fn () => actingAs(User::factory()->create()));

function pushPayload(string $actions): array
{
    return [
        'data' => [
            'id' => 'push-1',
            'title' => 'Order shipped',
            'body' => 'Your order is on the way',
            'actions' => $actions,
            'sendToDatabase' => '0',
        ],
    ];
}

/**
 * The notification the component is expected to show for pushPayload(), with the given actions.
 */
function expectedPush(array $actions): Notification
{
    return Notification::make('push-1')
        ->title('Order shipped')
        ->actions($actions)
        ->body('Your order is on the way')
        ->icon(null)
        ->iconColor(null)
        ->color(null)
        ->duration(null);
}

it('shows a pushed notification with a link action', function () {
    livewire(Firebase::class)
        ->call('fcmNotification', pushPayload(json_encode(['url' => 'https://tomatophp.com/orders/1'])))
        ->assertHasNoErrors();

    Notification::assertNotified(expectedPush([
        Action::make('view')
            ->label(trans('filament-actions::view.single.label'))
            ->url('https://tomatophp.com/orders/1')
            ->markAsRead(),
    ]));
});

it('shows a pushed notification with the actions of a filament notification', function () {
    livewire(Firebase::class)
        ->call('fcmNotification', pushPayload(json_encode([
            ['name' => 'open', 'label' => 'Open order', 'url' => 'https://tomatophp.com/orders/1'],
        ])))
        ->assertHasNoErrors();

    Notification::assertNotified(expectedPush([
        Action::make('open')
            ->label('Open order')
            ->color(null)
            ->icon(null)
            ->url('https://tomatophp.com/orders/1')
            ->markAsRead(false)
            ->markAsUnread(false),
    ]));
});

it('shows a pushed notification without actions', function () {
    livewire(Firebase::class)
        ->call('fcmNotification', pushPayload('[]'))
        ->assertHasNoErrors();

    Notification::assertNotified(expectedPush([]));
});
