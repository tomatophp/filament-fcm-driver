![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-fcm-driver/master/art/3x1io-tomato-fcm-driver.jpg)

# Filament fcm driver

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-fcm-driver/version.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)
[![License](https://poser.pugx.org/tomatophp/filament-fcm-driver/license.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)
[![Downloads](https://poser.pugx.org/tomatophp/filament-fcm-driver/d/total.svg)](https://packagist.org/packages/tomatophp/filament-fcm-driver)

Firebase Cloud Messaging driver for Filament Alerts Sender

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

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
