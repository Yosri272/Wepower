<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::all()->random();

        return [
            'shop_id' => $product->shop->id,
            'product_id' => $product->id,
            'customer_id' => Customer::all()->random()->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->paragraph,
        ];
    }
}
