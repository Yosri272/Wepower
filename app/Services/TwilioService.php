<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client($config->twilio_sid, $config->twilio_token);
    }

    public function sendMessage($phone, $message)
    {
        return $this->client->messages->create($phone, [
            'from' => $this->config->twilio_from,
            'body' => $message,
        ]);
    }
}
