<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rootUser = User::role(Roles::ROOT->value)->first();

        Shop::factory()->create([
            'user_id' => $rootUser->id,
            'name' => 'My Shop',
            'delivery_charge' => 0,
            'description' => 'My Shop Description',
            'status' => true,
            'min_order_amount' => 0,

        ]);

        $rootUser->assignRole(Roles::SHOP->value);
    }
}
