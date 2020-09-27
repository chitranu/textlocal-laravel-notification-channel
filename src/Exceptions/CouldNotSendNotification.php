<?php

namespace NotificationChannels\Textlocal\Exceptions;

use NotificationChannels\Textlocal\TextlocalMessage;

class CouldNotSendNotification extends \Exception
{
    public static function invalidReceiver()
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForTextlocal method or one of the conventional attributes to your notifiable.'
        );
    }

    public static function invalidMessageObject($message)
    {
        $type = is_object($message) ? get_class($message) : gettype($message);

        return new static(
            'Notification was not sent. The message should be a instance of `'.TextlocalMessage::class."` and a `{$type}` was given."
        );
    }
}
