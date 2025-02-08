<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebNotificationResource;
use App\Models\Notification;
use App\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    // fetch notifications for admin
    public function index()
    {
        $notifications = NotificationRepository::query()->where('shop_id', auth()->user()->shop->id)->orderBy('is_read', 'asc')->latest('id')->take(10)->get();

        $total = NotificationRepository::query()->where('shop_id', auth()->user()->shop->id)->whereIsRead(false)->count();

        return $this->json('nitifications', [
            'total' => $total >= 10 ? '9+' : $total,
            'notifications' => WebNotificationResource::collection($notifications),
        ]);
    }

    // mark as read
    public function markAsRead(Notification $notification)
    {

        $notification->update(['is_read' => true]);

        return redirect()->to($notification->url);
    }

    // show notification list
    public function show()
    {
        $notifications = NotificationRepository::query()->where('shop_id', auth()->user()->shop->id)->orderBy('is_read', 'asc')->latest('id')->paginate(20);

        return view('shop.notification', compact('notifications'));
    }

    // mark all as read
    public function markAllAsRead()
    {

        NotificationRepository::query()->where('shop_id', auth()->user()->shop->id)->update(['is_read' => true]);

        return back()->withSuccess(__('All notifications marked as read!'));
    }

    // destroy notification
    public function destroy(Notification $notification)
    {

        $notification->delete();

        return back()->withSuccess(__('Notification deleted!'));
    }
}
