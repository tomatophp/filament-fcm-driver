<?php

namespace TomatoPHP\FilamentFcmDriver\Services;

use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use TomatoPHP\FilamentAlerts\Models\UserToken;
use TomatoPHP\FilamentAlerts\Services\Drivers\Driver;
use TomatoPHP\FilamentFcmDriver\Jobs\NotifyFCMJob;

class FcmMobileDriver extends Driver
{
    public function setup(): void
    {
        // TODO: Implement setup() method.
    }

    public function sendIt(string $title, string $model, int | string | null $modelId = null, ?string $body = null, ?string $url = null, ?string $icon = null, ?string $image = null, ?string $type = 'info', ?string $action = 'system', ?array $data = [], ?int $template_id = null, ?Notification $notification = null): void
    {
        if ($notification) {
            $data = array_merge($data, [
                'id' => $notification->getId(),
                'actions' => json_encode($notification->getActions()),
                'body' => $notification->getBody(),
                'color' => $notification->getColor(),
                'duration' => $notification->getDuration(),
                'icon' => $notification->getIcon(),
                'iconColor' => $notification->getIconColor(),
                'status' => $notification->getStatus(),
                'title' => $notification->getTitle(),
                'view' => $notification->hasView() ? $notification->getView() : null,
                'viewData' => json_encode($notification->getViewData()),
                'data' => json_encode($data),
            ]);
        } else {
            $data = array_merge($data, [
                'id' => Str::random(6),
                'actions' => json_encode([]),
                'body' => $body,
                'color' => null,
                'duration' => null,
                'icon' => $icon,
                'iconColor' => null,
                'status' => $type,
                'title' => $title,
                'view' => null,
                'viewData' => null,
                'data' => json_encode($data),
            ]);
        }

        if ($model && $modelId) {
            $user = $model::find($modelId);
            $token = UserToken::query()
                ->where('model_id', $modelId)
                ->where('model_type', $model)
                ->where('provider', 'fcm-mobile')
                ->first();
            if ($token) {
                dispatch(new NotifyFCMJob([
                    'user' => $user,
                    'title' => $title,
                    'message' => $body,
                    'icon' => $icon,
                    'image' => $image,
                    'url' => $url,
                    'type' => 'fcm-mobile',
                    'data' => $data,
                    'sendToDatabase' => $data['sendToDatabase'] ?? config('filament-fcm-driver.database.save', false),
                ]));
            }
        }
    }
}
