![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/art/3x1io-tomato-fcm-driver.jpg)

# Filament Firebase Cloud Messages Driver

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-fcm-driver/version.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)
[![License](https://poser.pugx.org/tomatophp/filament-fcm-driver/license.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)
[![Downloads](https://poser.pugx.org/tomatophp/filament-fcm-driver/d/total.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)

Firebase Cloud Messaging driver for [Filament Alerts Sender](https://www.github.com/tomatophp/filament-alerts)

## Installation

```bash
composer require tomatophp/filament-fcm-driver
```
after install your package please run this command

```bash
php artisan filament-fcm-driver:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentFcmDriver\FilamentFcmDriverPlugin::make())
```

now you need to access Setting Hub page then go to Firebase options and then fill your data and save it. then please run this command to generate service worker file

```bash
php artisan filament-fcm:install
```

now on your User Model add this trait `InteractsWithFcm`

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use TomatoPHP\FilamentFcmDriver\Traits\InteractsWithFcm;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use InteractsWithFcm;
    ...
```

### Queue

the notification is run on queue, so you must run the queue worker to send the notifications

```bash
php artisan queue:work
```

## Usage

you can use the filament native notification and we add some macro for you

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Test title')
    ->body('Test body')
    ->icon('heroicon-o-bell')
    ->info()
    ->sendUse($user, DiscordDriver::class);
```

or you can send it directly from the user model

```php

$user->notifyFirebase(
    message: $this->message,
    type: $this->provider,
    title: $this->title,
    url: $this->url,
    image: $this->image,
    icon: $this->icon,
    data: [
        'url' => $this->url,
        'id' => $this->model_id,
        'actions' => [],
        'body' => $this->message,
        'color' => null,
        'duration' => null,
        'icon' => $this->icon,
        'iconColor' => null,
        'status' => null,
        'title' => $this->title,
        'view' => null,
        'viewData' => null,
        'data'=> $this->data
    ],
    sendToDatabase: false
);

```

or you can use FilamentAlerts Facade

```php

use TomatoPHP\FilamentAlerts\Facades\FilamentAlerts;
use TomatoPHP\FilamentFcmDriver\Services\FcmWebDriver;
use TomatoPHP\FilamentFcmDriver\Services\FcmMobileDriver;

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
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-fcm-driver-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-fcm-driver-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-fcm-driver-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-fcm-driver-migrations"
```

## Testing

if you like to run `PEST` testing just use this command

```bash
composer test
```

## Code Style

if you like to fix the code style just use this command

```bash
composer format
```

## PHPStan

if you like to check the code by `PHPStan` just use this command

```bash
composer analyse
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
