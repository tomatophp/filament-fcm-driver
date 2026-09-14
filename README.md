![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/arts/fadymondy-tomato-fcm-driver.jpg)

# Filament Firebase Cloud Messages Driver

[![Dependabot Updates](https://github.com/tomatophp/filament-fcm-driver/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/tomatophp/filament-fcm-driver/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/tomatophp/filament-fcm-driver/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/filament-fcm-driver/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/filament-fcm-driver/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-fcm-driver/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-fcm-driver/version.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)
[![License](https://poser.pugx.org/tomatophp/filament-fcm-driver/license.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)
[![Downloads](https://poser.pugx.org/tomatophp/filament-fcm-driver/d/total.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)

Firebase Cloud Messaging driver for [Filament Alerts Sender](https://www.github.com/tomatophp/filament-alerts)

## Screenshot

| Light | Dark |
|-------|------|
| ![Settings](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/arts/settings-light.png) | ![Settings](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/arts/settings-dark.png) |
| ![Settings Hub](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/arts/settings-hub-light.png) | ![Settings Hub](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/arts/settings-hub-dark.png) |
| ![Drivers](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/arts/drivers-light.png) | ![Drivers](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/arts/drivers-dark.png) |

## Requirements

| Package version | Filament | Laravel     | PHP  |
|-----------------|----------|-------------|------|
| 5.x             | 5.x      | 12.x, 13.x  | 8.2+ |
| 4.x             | 4.x      | 11.x, 12.x  | 8.2+ |

## Installation

```bash
composer require tomatophp/filament-fcm-driver -W
```

> `-W` lets Composer upgrade Firebase dependencies your lock file already pins (for example `lcobucci/jwt` 4.x), which is what made the install fail in [#9](https://github.com/tomatophp/filament-fcm-driver/issues/9).

after install your package please run this command

```bash
php artisan filament-fcm-driver:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentFcmDriver\FilamentFcmDriverPlugin::make())
```

now you need to access Setting Hub page then go to Firebase options and then fill your data and save it. then run the install command again to generate the `public/firebase-messaging-sw.js` service worker file with your Firebase config

```bash
php artisan filament-fcm-driver:install
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
