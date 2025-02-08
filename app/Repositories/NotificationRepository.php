<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Notification::class;
    }

    // get auth user notification list
    public static function authNotifyListByStatus($status = null)
    {
        return self::model()::query()->where('user_id', auth()->id())->when($status, function ($query) use ($status) {
            $query->where('is_read', $status);
        })->orderBy('is_read', 'asc')->orderBy('id', 'desc')->get();
    }

    // store new notification
    public static function storeByRequest($request): Notification
    {
        return self::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id ?? null,
            'shop_id' => $request->shop_id ?? null,
            'url' => $request->url ?? null,
            'icon' => $request->icon ?? null,
            'type' => $request->type ?? null,
            'withdraw_id' => $request->withdraw_id ?? null,
        ]);
    }

    // mark as read
    public static function readUpdateByRequest(Notification $notification): Notification
    {
        $notification->update([
            'is_read' => 1,
        ]);

        return $notification;
    }
}
