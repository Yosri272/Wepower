<?php

namespace App\Services;

use App\Models\SMSConfig;

class SmsGatewayService
{
    protected $gateway;

    public function __construct()
    {
        $smsConfig = SMSConfig::where('status', true)->first();

        if ($smsConfig) {
            $config = json_decode($smsConfig->data);

            switch ($smsConfig->provider) {
                case 'twilio':
                    $this->gateway = new TwilioService($config);
                    break;
                case 'message_bird':
                    $this->gateway = new MessageBirdService($config);
                    break;
                case 'nexmo':
                    $this->gateway = new NexmoService($config);
                    break;
                case 'telesign':
                    $this->gateway = new TelesignService($config);
                    break;
                default:
                    throw new \Exception('No SMS gateway selected.');
            }
        }
    }

    public function sendSMS($to, $message)
    {
        if (! $this->gateway) {
            throw new \Exception('SMS gateway not selected.');
        }

        return $this->gateway->sendMessage($to, $message);
    }
}
