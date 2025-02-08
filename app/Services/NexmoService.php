<?php

namespace App\Services;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class NexmoService
{
    protected $client;

    protected $config;

    public function __construct($config)
    {
        $basic = new Basic($config->nexmo_key, $config->nexmo_secret);
        $this->client = new Client($basic);
        $this->config = $config;
    }

    public function sendMessage($to, $message)
    {
        $response = $this->client->sms()->send(new SMS($to, $this->config->nexmo_from, $message));

        return $response;
    }
}
