<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banner::factory(rand(15, 50))->create();

        for ($i = 0; $i < 4; $i++) {
            Banner::factory(1)->create([
                'shop_id' => null,
            ]);
        }
    }
}
