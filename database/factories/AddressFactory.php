<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $address_type = ['Home', 'office', 'other'];

        return [
            'customer_id' => Customer::all()->random()->id,
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address_type' => $this->faker->randomElement($address_type),
            'road_no' => $this->faker->streetName(),
            'house_no' => $this->faker->randomNumber(),
            'flat_no' => $this->faker->randomDigitNotZero(),
            'area' => $this->faker->city(),
            'address_line' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->streetAddress(),
            'post_code' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'is_default' => $this->faker->boolean(),
        ];
    }
}
