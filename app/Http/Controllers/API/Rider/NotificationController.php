<?php

namespace App\Http\Controllers\API\Rider;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = NotificationRepository::authNotifyListByStatus(request('is_read'));

        return $this->json('Notification list', [
            'notification' => NotificationResource::collection($notifications),
        ]);
    }

    public function update(Notification $notification)
    {
        $notification = NotificationRepository::readUpdateByRequest($notification);

        return $this->json('Notification read successfully', [
            'notification' => NotificationResource::make($notification),
        ]);
    }

    public function delete($notification)
    {
        $notification = NotificationRepository::find($notification);

        if (! $notification) {
            return $this->json('Notification not found', [], 422);
        }

        $notification->delete();

        return $this->json('Notification deleted successfully');
    }
}
