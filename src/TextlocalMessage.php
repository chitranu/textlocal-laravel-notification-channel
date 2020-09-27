<?php

namespace NotificationChannels\Textlocal;

class TextlocalMessage
{
    /**
     * Message body.
     *
     * @var string
     */
    public $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }

    /**
     * Get the message body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
