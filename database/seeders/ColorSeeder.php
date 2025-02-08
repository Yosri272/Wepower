<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['red', 'blue', 'green', 'yellow', 'orange', 'purple', 'black', 'white',
        ];
        $shops = Shop::all();

        foreach ($shops as $shop) {
            foreach ($colors as $color) {
                $shop->colors()->create([
                    'name' => $color,
                ]);
            }
        }

    }
}
