<?php

namespace TomatoPHP\FilamentFcmDriver\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TomatoPHP\FilamentAlerts\Models\NotificationsLogs;
use TomatoPHP\FilamentFcmDriver\Notifications\FCMNotificationService;

class NotifyFCMJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public ?Model $user;

    public ?string $title;

    public ?string $message;

    public ?string $image;

    public ?string $icon;

    public ?string $url;

    public ?string $type;

    public ?array $data;

    public ?bool $sendToDatabase = true;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($arrgs)
    {
        $this->user = $arrgs['user'];
        $this->title = $arrgs['title'];
        $this->message = $arrgs['message'];
        $this->icon = $arrgs['icon'];
        $this->url = $arrgs['url'];
        $this->image = $arrgs['image'];
        $this->type = $arrgs['type'];
        $this->data = $arrgs['data'];
        $this->sendToDatabase = $arrgs['sendToDatabase'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = new NotificationsLogs;
        $log->title = $this->title;
        $log->description = $this->message;
        $log->provider = $this->type;
        $log->type = 'info';
        $log->save();

        try {
            $this->user->setFCM($this->type)->notify(new FCMNotificationService(
                $this->message,
                $this->type,
                $this->title,
                $this->icon,
                $this->image,
                $this->url,
                $this->data,
                $this->sendToDatabase,
            ));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
