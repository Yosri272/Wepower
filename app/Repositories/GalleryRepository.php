<?php

namespace App\Repositories;

use App\Models\Gallery;
use Arafat\LaravelRepository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GalleryRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Gallery::class;
    }

    /**
     * @param  Request  $request
     */
    public static function uploadByRequest($extractPath, $folderName): array
    {
        $extractedFile = File::allFiles(storage_path($extractPath));

        $total = self::storefileToDatabase($extractedFile, $folderName);

        File::deleteDirectory(storage_path($extractPath));

        return [
            'total' => $total,
        ];
    }

    /**
     * store file to database
     *
     * @return total
     */
    private static function storefileToDatabase($extractedFile, $folderName)
    {
        $invaildFiles = [];

        $shop = auth()->user()->shop;

        $total = 0;

        foreach ($extractedFile as $file) {

            $originalName = pathinfo($file, PATHINFO_BASENAME);

            if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'png', 'jpeg', 'gif'])) {

                $filePath = 'gallery/shop'.$shop->id.'/'.$folderName;

                Storage::disk('public')->put($filePath.'/'.$originalName, File::get($file));

                $total = $total + 1;
            } else {
                $invaildFiles[] = $file;
            }
        }

        self::create([
            'shop_id' => $shop->id,
            'name' => $folderName,
            'total_image' => $total,
        ]);

        return $total;
    }
}
