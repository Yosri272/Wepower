<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'media_id' => Media::factory()->create(),
            'shop_id' => Shop::all()->random()->id,
            'description' => $this->faker->sentence(),
            'status' => $this->faker->boolean(),
        ];
    }
}
