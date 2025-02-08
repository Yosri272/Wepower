<?php

namespace App\Repositories;

use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use App\Services\NotificationServices;

class DriverRepository extends Repository
{
    public static function model()
    {
        return Driver::class;
    }

    public static function storeByUser(User $user)
    {
        $driver = self::create([
            'user_id' => $user->id,
        ]);

        self::getWallet($driver);

        return $driver;
    }

    public static function getAllDeactive()
    {
        $drivers = self::model()::query();
        $active = 0;
        $drivers = $drivers->whereHas('user', function ($user) use ($active) {
            $user->where('is_active', $active);
        });

        return $drivers->latest('id')->get();
    }

    public static function findById($id)
    {
        return self::find($id);
    }

    public static function getWallet(Driver $driver)
    {

        $wallet = $driver->user?->wallet;

        if (! $wallet) {
            $wallet = WalletRepository::storeByRequest($driver->user);
        }

        return $wallet;
    }

    public static function assignOrder(Order $order, Driver $driver): Driver
    {
        $driver->driverOrders()->create([
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'is_completed' => false,
            'assign_for' => 'delivery',
            'is_accept' => false,
        ]);

        $message = 'New order assigned to you. OrderID: '.$order->prefix.$order->order_code;
        $title = 'New Order Assigned';

        $deviceKeys = $driver->user->devices->pluck('key')->toArray();

        try {
            NotificationServices::sendNotification($message, $deviceKeys, $title);
        } catch (\Throwable $th) {}

        NotificationRepository::storeByRequest((object) [
            'user_id' => $driver->user_id,
            'title' => $title,
            'content' => $message,
            'type' => 'order',
            'url' => $order->id,
            'icon' => null,
            'withdraw_id' => null,
        ]);

        return $driver;
    }
}
