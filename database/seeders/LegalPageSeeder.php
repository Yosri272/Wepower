<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Faker\Factory;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        // Legal Pages
        $legalPages = [
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'description' => $faker->randomHtml(),
            ],
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'description' => $faker->randomHtml(),
            ],
            [
                'title' => 'Return and Refund Policy',
                'slug' => 'return-and-refund-policy',
                'description' => $faker->randomHtml(),
            ],
            [
                'title' => 'Shipping and Delivery Policy',
                'slug' => 'shipping-and-delivery-policy',
                'description' => $faker->randomHtml(),
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'description' => $faker->randomHtml(4, rand(4, 10)),
            ],
        ];

        foreach ($legalPages as $legalPage) {
            LegalPage::create($legalPage);
        }
    }
}
