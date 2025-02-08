<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebNotificationResource;
use App\Models\Notification;
use App\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    // fetch notifications for admin
    public function index()
    {
        $notifications = NotificationRepository::query()->whereNull('shop_id')->whereNull('user_id')
            ->orderBy('is_read', 'asc')->latest('id')->take(10)->get();

        $total = NotificationRepository::query()->whereNull('shop_id')->whereNull('user_id')->whereIsRead(false)->count();

        return $this->json('nitifications', [
            'total' => $total >= 10 ? '9+' : $total,
            'notifications' => WebNotificationResource::collection($notifications),
        ]);
    }

    // show all notifications
    public function show()
    {

        $notifications = NotificationRepository::query()->whereNull('shop_id')->whereNull('user_id')->orderBy('is_read', 'asc')->latest('id')->paginate(20);

        return view('admin.notification', compact('notifications'));
    }

    // mark as read
    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        if ($notification->url != null) {
            return redirect()->to($notification->url);
        }

        return back();
    }

    // mark all as read
    public function markAllAsRead()
    {

        NotificationRepository::query()->whereNull('shop_id')->whereNull('user_id')->update(['is_read' => true]);

        return back()->withSuccess(__('All notifications marked as read!'));
    }

    // destroy notification
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return back()->withSuccess(__('Notification deleted successfully'));
    }
}
