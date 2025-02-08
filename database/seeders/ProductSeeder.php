<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 1; $i <= rand(50, 120); $i++) {
            $product = Product::factory()->create();

            for ($j = 0; $j < 4; $j++) {
                $media = Media::factory()->create();
                $product->medias()->attach($media);
            }

            $colors = $product->shop->colors;
            $product->colors()->attach($colors->random(3));

            $sizes = $product->shop->sizes;
            $product->sizes()->attach($sizes->random(4));

            $categories = $product->shop->categories;
            $product->categories()->attach($categories->random(3));
        }
    }
}
