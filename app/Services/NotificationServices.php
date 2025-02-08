<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationServices
{
    public static function sendNotification(string $body, array $tokens, $title = null)
    {
        $notification = Notification::create($title, $body);

        $firebaseCredentials = storage_path('app/public/firebase_credentials.json');

        if (!file_exists($firebaseCredentials)) {
            return false;
        }

        $messaging = (new Factory)->withServiceAccount($firebaseCredentials)->createMessaging();

        $message = CloudMessage::new()->withNotification($notification);

        try {
            $messaging->sendMulticast($message, $tokens);
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}
