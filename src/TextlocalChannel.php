<?php

namespace NotificationChannels\Textlocal;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\Textlocal\Exceptions\CouldNotSendNotification;

class TextlocalChannel
{
    /**
     * @var Textlocal
     */
    protected $textlocal;

    public function __construct(Textlocal $textlocal, Dispatcher $events)
    {
        $this->textlocal = $textlocal;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Textlocal\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $destination = $this->getDestination($notifiable, $notification);
            $message = $this->getMessage($notifiable, $notification);

            return $this->textlocal->send($message->getBody(), $destination);
        } catch (\Exception $e) {
            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'Textlocal',
                ['message' => $e->getMessage(), 'exception' => $e]
            );
            $this->events->dispatch($event);
        }
    }

    /**
     * Get the phone number to send a notification to.
     *
     * @throws CouldNotSendNotification
     */
    protected function getDestination($notifiable, Notification $notification)
    {
        if ($to = $notifiable->routeNotificationFor('Textlocal', $notification)) {
            return $to;
        }

        return $this->guessDestination($notifiable);
    }

    /**
     * Try to get the phone number from some commonly used attributes for that.
     *
     * @throws CouldNotSendNotification
     */
    protected function guessDestination($notifiable)
    {
        $commonAttributes = ['phone', 'phone_number', 'mobile', 'full_phone'];
        foreach ($commonAttributes as $attribute) {
            if (isset($notifiable->{$attribute})) {
                return $notifiable->{$attribute};
            }
        }

        throw CouldNotSendNotification::invalidReceiver();
    }

    /**
     * Get the SNS Message object.
     *
     * @throws CouldNotSendNotification
     */
    protected function getMessage($notifiable, Notification $notification): TextlocalMessage
    {
        $message = $notification->toSms($notifiable);

        if (is_string($message)) {
            return new TextlocalMessage($message);
        }

        if ($message instanceof TextlocalMessage) {
            return $message;
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }
}
