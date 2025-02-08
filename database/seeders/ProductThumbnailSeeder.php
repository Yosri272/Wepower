<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductThumbnailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {

            $thumbnail = $product->media;
            if ($thumbnail && Storage::exists($thumbnail->src) && $thumbnail->original_name == null) {
                $extension = pathinfo($thumbnail->src, PATHINFO_EXTENSION);
                $thumbnail->update([
                    'original_name' => 'thumbnail'.$product->id.'_1.'.$extension,
                ]);
            }

            $medias = $product->medias;

            $serial = 1;
            foreach ($medias as $media) {
                if ($media && Storage::exists($media->src) && $media->original_name == null) {
                    $serial = $serial + 1;

                    $extension = pathinfo($media->src, PATHINFO_EXTENSION);
                    $media->update([
                        'original_name' => 'thumbnail'.$product->id.'_'.$serial.'.'.$extension,
                    ]);
                }
            }
        }
    }
}
