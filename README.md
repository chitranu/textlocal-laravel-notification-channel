# Textlocal notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/chitranu/textlocal-laravel-notification-channel.svg?style=flat-square)](https://packagist.org/packages/chitranu/textlocal-laravel-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/chitranu/textlocal-laravel-notification-channel/master.svg?style=flat-square)](https://travis-ci.org/chitranu/textlocal-laravel-notification-channel)
[![StyleCI](https://styleci.io/repos/65772445/shield)](https://styleci.io/repos/65772445)
[![Quality Score](https://img.shields.io/scrutinizer/g/chitranu/textlocal-laravel-notification-channel.svg?style=flat-square)](https://scrutinizer-ci.com/g/chitranu/textlocal-laravel-notification-channel)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/chitranu/textlocal-laravel-notification-channel/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/chitranu/textlocal-laravel-notification-channel/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/chitranu/textlocal-laravel-notification-channel.svg?style=flat-square)](https://packagist.org/packages/chitranu/textlocal-laravel-notification-channel)

This package makes it easy to send notifications using [Textlocal](https://textlocal.in/) with Laravel framework.

## Contents

-   [Installation](#installation)
    -   [Setting up the Textlocal service](#setting-up-the-Textlocal-service)
-   [Usage](#usage)
    -   [Available Message methods](#available-message-methods)
-   [Changelog](#changelog)
-   [Testing](#testing)
-   [Security](#security)
-   [Contributing](#contributing)
-   [Credits](#credits)
-   [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require chitranu/textlocal-laravel-notification-channel
```


### Setting up the Textlocal SMS service

Add your Textlocal API key and sender to your `config/services.php`:

```php
<?php

return [

    // ...

    'textlocal' => [
        'key' => env('TEXTLOCAL_KEY'),
        'sender' => env('TEXTLOCAL_SENDER'),
    ],

];
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
<?php

use NotificationChannels\Textlocal\TextlocalChannel;
use NotificationChannels\Textlocal\TextlocalMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TextlocalChannel::class];
    }

    public function toSms($notifiable)
    {
        // You can just return a plain string:
        return "Your {$notifiable->service} account was approved!";

        // OR explicitly return a TextlocalMessage object passing the message body:
        return new TextlocalMessage("Your {$notifiable->service} account was approved!");

        // OR return a TextlocalMessage passing the arguments via `create()` or `__construct()`:
        return TextlocalMessage::create([
            'body' => "Your {$notifiable->service} account was approved!",
            'transactional' => true,
            'sender' => 'MyBusiness',
        ]);

        // OR create the object with or without arguments and then use the fluent API:
        return TextlocalMessage::create()
            ->body("Your {$notifiable->service} account was approved!")
            ->promotional()
            ->sender('MyBusiness');
    }
}
```

In order to let your Notification know which phone are you sending to, the channel
will look for the `phone`, `mobile`, `phone_number` or `full_phone` attribute of the
Notifiable model. If you want to override this behaviour, add the
`routeNotificationForTextlocal` method to your Notifiable model.

```php
<?php

use Illuminate\Notifications\Notifiable;

class SomeModel {
    use Notifiable;

    public function routeNotificationForTextlocal($notification)
    {
        return '+1234567890';
    }
}
```

### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email hey@swapnil.dev instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Swapnil Bhavsar](https://github.com/IamSwap)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
