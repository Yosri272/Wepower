<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\DriverOrder;
use App\Models\Order;
use Illuminate\Database\Seeder;

class RiderOrderDeleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::query()->update([
            'order_status' => OrderStatus::CONFIRM->value,
        ]);

        DriverOrder::query()->delete();
    }
}
