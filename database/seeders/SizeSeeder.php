<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = Shop::all();

        foreach ($shops as $shop) {
            $sizes = ['s', 'm', 'l', 'xl', 'xxl', 'xxxl'];
            foreach ($sizes as $size) {
                $shop->sizes()->create([
                    'name' => $size,
                ]);
            }
        }
    }
}
