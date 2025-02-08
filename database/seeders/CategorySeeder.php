<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            "Women's", "Men's", 'Beauty',
            'Jewelry', 'Home', 'Kids',
            'Electronics', 'Sports', 'Books',
        ];

        foreach ($categories as $category) {
            Category::factory()->create([
                'name' => $category,
            ]);
        }
    }
}
