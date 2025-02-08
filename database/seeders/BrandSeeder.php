<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Media;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = ['Nike', 'Adidas', 'Apple', 'Samsung', 'Sony', 'HP', 'Dell', 'Lenovo', 'Canon', 'Sony', 'LG', 'Microsoft', 'Puma', 'H&M', 'Zara', 'Gucci', 'Toyota', 'Honda', 'BMW', 'Mercedes-Benz'];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'media_id' => Media::factory()->create()->id,
                'is_default' => true,
            ]);
        }
    }
}
