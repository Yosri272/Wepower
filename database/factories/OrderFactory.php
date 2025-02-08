<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orderStatus = OrderStatus::cases();

        return [
            'shop_id' => Shop::all()->random()->id,
            'customer_id' => Customer::all()->random()->id,
            'coupon_id' => Coupon::all()->random()->id,
            'coupon_discount' => $this->faker->randomFloat(2, 10, 100),
            'order_code' => rand(0000, 9999),
            'prefix' => 'RC',
            'discount' => $this->faker->randomFloat(2, 10, 100),
            'pick_date' => $this->faker->dateTimeBetween('-1 years', now())->format('Y-m-d H:i:s'),
            'delivery_date' => $this->faker->dateTimeBetween('-1 years', now())->format('Y-m-d H:i:s'),
            'payable_amount' => $this->faker->randomFloat(2, 50, 500),
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
            'payment_status' => $this->faker->randomElement(PaymentStatus::cases())->value,
            'payment_method' => $this->faker->randomElement(PaymentMethod::cases())->value,
            'order_status' => $this->faker->randomElement($orderStatus)->value,
            'address_id' => Address::factory()->create(),
            'delivery_charge' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
