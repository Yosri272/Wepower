<?php

namespace App\Services;

use telesign\sdk\messaging\MessagingClient;

class TelesignService
{
    protected $client;

    public function __construct($config)
    {
        $customerId = $config->customer_id;
        $apiKey = $config->api_key;
        $this->client = new MessagingClient($customerId, $apiKey);
    }

    public function sendMessage($to, $message)
    {
        return $this->client->message($to, $message, 'ARN');
    }
}
