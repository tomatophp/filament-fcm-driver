<?php

namespace TomatoPHP\FilamentFcmDriver\Livewire;

use Detection\MobileDetect;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class Firebase extends Component
{
    #[On('fcm-token')]
    public function fcmToken(string $token)
    {
        $detect = new MobileDetect;
        if (auth()->user()) {
            $user = auth()->user();
            $getToken = $user->setFCM($detect->isMobile() ? 'fcm-mobile' : 'fcm-web')->userTokensFcm()->where('provider', $detect->isMobile() ? 'fcm-mobile' : 'fcm-web')->first();
            if ($getToken) {
                $getToken->provider_token = $token;
                $getToken->save();
            } else {
                $user->setFCM($detect->isMobile() ? 'fcm-mobile' : 'fcm-web')->userTokensFcm()->create([
                    'provider' => $detect->isMobile() ? 'fcm-mobile' : 'fcm-web',
                    'provider_token' => $token,
                ]);
            }
        }
    }

    #[On('fcm-notification')]
    public function fcmNotification(mixed $data)
    {
        $actions = $this->actionsFrom($data['data']['actions'] ?? null);

        if (isset($data['data']['sendToDatabase']) && $data['data']['sendToDatabase'] === '1') {
            Notification::make($data['data']['id'])
                ->title($data['data']['title'])
                ->actions($actions)
                ->body($data['data']['body'])
                ->icon($data['data']['icon'] ?? null)
                ->iconColor($data['data']['iconColor'] ?? null)
                ->color($data['data']['color'] ?? null)
                ->duration($data['data']['duration'] ?? null)
                ->send()
                ->sendToDatabase(auth()->user());
        } else {
            Notification::make($data['data']['id'])
                ->title($data['data']['title'])
                ->actions($actions)
                ->body($data['data']['body'])
                ->icon($data['data']['icon'] ?? null)
                ->iconColor($data['data']['iconColor'] ?? null)
                ->color($data['data']['color'] ?? null)
                ->duration($data['data']['duration'] ?? null)
                ->send();
        }
    }

    /**
     * Rebuild the notification actions sent in the push payload: either the serialized
     * actions of a Filament notification (a JSON list) or a single `{"url": ...}` link.
     *
     * @return array<int, Action>
     */
    protected function actionsFrom(mixed $payload): array
    {
        $decoded = is_string($payload) ? json_decode($payload, true) : $payload;

        if (! is_array($decoded) || $decoded === []) {
            return [];
        }

        if (! array_is_list($decoded)) {
            return filled($decoded['url'] ?? null)
                ? [Action::make('view')->label(trans('filament-actions::view.single.label'))->url($decoded['url'])->markAsRead()]
                : [];
        }

        $actions = [];

        foreach ($decoded as $action) {
            if (! is_array($action) || blank($action['name'] ?? null)) {
                continue;
            }

            $actions[] = Action::make($action['name'])
                ->label($action['label'] ?? $action['name'])
                ->color($action['color'] ?? null)
                ->icon($action['icon'] ?? null)
                ->url($action['url'] ?? null)
                ->markAsRead((bool) ($action['shouldMarkAsRead'] ?? false))
                ->markAsUnread((bool) ($action['shouldMarkAsUnread'] ?? false));
        }

        return $actions;
    }

    public function render()
    {
        return view('filament-fcm-driver::firebase-base');
    }
}
