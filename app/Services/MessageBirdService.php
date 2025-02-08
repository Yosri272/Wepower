<?php

namespace App\Services;

use MessageBird\Client;
use MessageBird\Objects\Message;

class MessageBirdService
{
    protected $client;

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;

        $this->client = new Client($config->api_key);
    }

    public function sendMessage($phone, $message)
    {
        $messageObject = new Message();
        $messageObject->originator = $this->config->from;
        $messageObject->recipients = [$phone];
        $messageObject->body = $message;

        return $this->client->messages->create($messageObject);
    }
}
