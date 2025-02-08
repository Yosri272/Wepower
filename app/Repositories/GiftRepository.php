<?php

namespace App\Repositories;

use App\Http\Requests\ShopGiftRequest;
use App\Models\Gift;
use Arafat\LaravelRepository\Repository;

class GiftRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Gift::class;
    }

    public static function storeByRequest(ShopGiftRequest $request): Gift
    {
        $thumbnail = MediaRepository::storeByRequest(
            $request->file('thumbnail'),
            'gifts',
            'image'
        );

        return self::create([
            'shop_id' => auth()->user()->shop->id,
            'name' => $request->name,
            'price' => $request->price,
            'media_id' => $thumbnail ? $thumbnail->id : null,
        ]);
    }

    public static function updateByRequest(ShopGiftRequest $request, Gift $gift): Gift
    {
        $thumbnail = $gift->media;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = MediaRepository::updateByRequest(
                $request->file('thumbnail'),
                'gifts',
                'image',
                $thumbnail
            );
        }
        $gift->update([
            'name' => $request->name,
            'price' => $request->price,
            'media_id' => $thumbnail ? $thumbnail->id : null,
        ]);

        return $gift;
    }
}
