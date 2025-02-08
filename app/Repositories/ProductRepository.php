<?php

namespace App\Repositories;

use App\Http\Requests\ProductRequest;
use App\Models\GeneraleSetting;
use App\Models\Media;
use App\Models\Product;
use App\Models\RecentView;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Product::class;
    }

    public static function recentView(Product $product)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            RecentView::where('product_id', $product->id)->where('user_id', $user->id)->firstOrcreate([
                'product_id' => $product->id,
                'user_id' => $user->id,
            ])?->update(['updated_at' => now()]);
        }

        return $product;
    }

    /**
     * store new product.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     *                                                      return \App\Models\Product
     */
    public static function storeByRequest(ProductRequest $request): Product
    {
        $thumbnail = MediaRepository::storeByRequest($request->thumbnail, 'products', 'thumbnail', 'image');

        $generaleSetting = GeneraleSetting::first();
        $approve = $generaleSetting?->new_product_approval ? false : true;

        $isAdmin = auth()->user()->hasRole('root');

        $product = self::create([
            'shop_id' => auth()->user()->shop->id,
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'brand_id' => $request->brand,
            'unit_id' => $request->unit,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'quantity' => $request->quantity,
            'min_order_quantity' => $request->min_order_quantity,
            'media_id' => $thumbnail->id,
            'is_active' => $isAdmin ? true : $approve,
            'is_new' => true,
            'is_approve' => $isAdmin ? true : $approve,
            'code' => $request->code,
        ]);

        $product->categories()->sync($request->category);
        $product->subcategories()->sync($request->sub_category);
        $product->colors()->sync($request->color);

        foreach ($request->size ?? [] as $size) {
            $product->sizes()->attach($size['id'], ['price' => $size['price']]);
        }

        foreach ($request->additionThumbnail as $additionThumbnail) {
            $thumbnail = MediaRepository::storeByRequest($additionThumbnail, 'products', 'thumbnail', 'image');
            $product->medias()->attach($thumbnail->id);
        }

        return $product;
    }

    /**
     * Update the product.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     *                                                      return \App\Models\Product
     */
    public static function updateByRequest(ProductRequest $request, Product $product): Product
    {
        $thumbnail = $product->media;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = MediaRepository::updateByRequest(
                $request->thumbnail,
                'products',
                'image',
                $thumbnail
            );
        }

        $generaleSetting = GeneraleSetting::first();
        $approve = $generaleSetting?->update_product_approval ? false : true;

        $isAdmin = auth()->user()->hasRole('root');

        self::update($product, [
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'brand_id' => $request->brand,
            'unit_id' => $request->unit,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'quantity' => $request->quantity,
            'min_order_quantity' => $request->min_order_quantity,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'is_active' => $isAdmin ? true : $approve,
            'is_new' => false,
            'is_approve' => $isAdmin ? true : $approve,
            'code' => $request->code,
        ]);

        $product->categories()->sync($request->category);
        $product->subcategories()->sync($request->sub_category);
        $product->colors()->sync($request->color);

        if ($request->has('size')) {
            $product->sizes()->detach();
            foreach ($request->size ?? [] as $size) {
                $product->sizes()->attach($size['id'], ['price' => $size['price']]);
            }
        }

        foreach ($request->additionThumbnail ?? [] as $additionThumbnail) {
            $thumbnail = MediaRepository::storeByRequest($additionThumbnail, 'products', 'thumbnail', 'image');
            $product->medias()->attach($thumbnail->id);
        }

        self::updatePreviousThumbnail($request->previousThumbnail);

        return $product;
    }

    /**
     * store new product from bulk import.
     */
    public static function bulkItemStore($rows, $folders = null)
    {
        $invalidRows = [];

        $shop = auth()->user()->shop;

        $total = 0;

        $folders = $folders !== null ? array_keys($folders) : [];

        $galleryPath = 'gallery/shop' . $shop->id;

        foreach ($rows as $row) {

            $createData = [];

            for ($i = 0; $i <= 13; $i++) {

                if ($i == 1) {
                    $createData['name'] = $row[$i];
                } elseif ($i == 2) {

                    $explodeThumbnails = explode(',', $row[$i]);

                    $thumbnails = [];
                    foreach ($explodeThumbnails as $thumbnail) {
                        $storeFile = null;
                        foreach ($folders as $folder) {
                            if (Storage::disk('public')->exists($galleryPath . '/' . $folder)) {
                                $files = File::files(Storage::disk('public')->path($galleryPath . '/' . $folder));
                                foreach ($files as $file) {
                                    if (basename($file) == $thumbnail) {
                                        $storeFile = $file;
                                        break;
                                    }
                                }
                            }
                        }

                        if ($storeFile) {
                            $thumbnails[] = $storeFile;
                        }
                    }
                    $createData['thumbnails'] = $thumbnails;
                } elseif ($i == 3) {
                    $selectCategories = explode(',', $row[$i]);
                    $categorys = [];
                    foreach ($selectCategories as $categoryName) {

                        $category = $shop->categories()->where('name', $categoryName)->first();

                        if ($category) {
                            $categorys[] = $category->id;
                        }
                    }
                    $createData['categorys'] = $categorys;
                } elseif ($i == 4) {
                    $selectedSubCategories = explode(',', $row[$i]);
                    $subCategories = [];
                    foreach ($selectedSubCategories as $subCategoryName) {
                        $subCategory = $shop->subcategories()->where('name', $subCategoryName)->first();
                        if ($subCategory) {
                            $subCategories[] = $subCategory->id;
                        }
                    }
                    $createData['subCategories'] = $subCategories;
                } elseif ($i == 5) {
                    $brand = $shop->brands()->where('name', $row[$i])->first();
                    $createData['brand'] = $brand ? $brand->id : null;
                } elseif ($i == 6) {
                    $selectColors = explode(',', $row[$i]);
                    $colors = [];
                    foreach ($selectColors as $colorName) {
                        $color = $shop->colors()->where('name', $colorName)->first();
                        if ($color) {
                            $colors[] = $color->id;
                        }
                    }
                    $createData['colors'] = $colors;
                } elseif ($i == 7) {
                    $selectSizes = explode(',', $row[$i]);
                    $sizes = [];
                    foreach ($selectSizes as $sizeName) {
                        $size = $shop->sizes()->where('name', $sizeName)->first();
                        if ($size) {
                            $sizes[] = $size->id;
                        }
                    }
                    $createData['sizes'] = $sizes;
                } elseif ($i == 8) {
                    $createData['price'] = $row[$i];
                } elseif ($i == 9) {
                    $createData['discount_price'] = $row[$i];
                } elseif ($i == 10) {
                    $createData['sku'] = $row[$i];
                } elseif ($i == 11) {
                    $createData['stock_quantity'] = $row[$i];
                } elseif ($i == 12) {
                    $createData['short_description'] = $row[$i];
                } elseif ($i == 13) {
                    $createData['description'] = $row[$i];
                }
            }

            if ($createData['name'] != null && $createData['price'] != null && count($createData['categorys']) != 0) {

                if ($createData['price'] < $createData['discount_price']) {
                    $createData['discount_price'] = $createData['price'];
                }

                self::storeBulkProduct($createData);

                $total = $total + 1;
            }
        }

        return $total;
    }

    /**
     * store new product from bulk import.
     *
     * @return Product
     */
    private static function storeBulkProduct($data)
    {
        $generaleSetting = GeneraleSetting::first();
        $approve = $generaleSetting?->new_product_approval ? false : true;

        $isAdmin = auth()->user()->hasRole('root');

        $thumbnail = $data['thumbnails'] ? $data['thumbnails'][0] : null;

        $media = self::storeMedia($thumbnail);

        $additionalThumbnails = $data['thumbnails'] ? array_slice($data['thumbnails'], 1) : [];

        $medias = [];
        foreach ($additionalThumbnails as $thumbnail) {
            $hasMedia = self::storeMedia($thumbnail);
            if ($hasMedia) {
                $medias[] = $hasMedia;
            }
        }

        $product = self::create([
            'shop_id' => auth()->user()->shop->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? 'description',
            'short_description' => $data['short_description'] ?? 'short description',
            'brand_id' => $data['brand'] ?? null,
            'price' => $data['price'] ?? 0,
            'discount_price' => $data['discount_price'] ?? 0,
            'quantity' => $data['stock_quantity'] ?? 1,
            'min_order_quantity' => 1,
            'media_id' => $media,
            'is_active' => $isAdmin ? true : $approve,
            'is_new' => true,
            'is_approve' => $isAdmin ? true : $approve,
            'code' => $data['sku'] ?? random_int(100000, 999999),
        ]);

        $product->categories()->sync($data['categorys'] ?? []);
        $product->subCategories()->sync($data['subCategories'] ?? []);
        $product->colors()->sync($data['colors'], []);
        $product->sizes()->sync($data['sizes'], []);

        $product->medias()->attach($medias);

        return $product;
    }

    public static function storeMedia($thumbnail)
    {
        if ($thumbnail != null) {

            $realPath = $thumbnail->getRealPath();

            $path = 'thumbnails';

            $fileName = random_int(100000, 999999) . date('YmdHis') . '.' . pathinfo($realPath, PATHINFO_EXTENSION);

            $storagePath = Storage::disk('public')->putFileAs($path, $thumbnail, $fileName);

            $media = Media::create([
                'name' => pathinfo($storagePath, PATHINFO_FILENAME),
                'src' => $storagePath,
                'type' => 'image',
                'original_name' => basename($realPath),
                'extension' => pathinfo($storagePath, PATHINFO_EXTENSION),
            ]);

            return $media->id;
        }

        return null;
    }

    /**
     * Update the previous thumbnails.
     *
     * @param  array  $previousThumbnails  The array of previous thumbnails
     */
    private static function updatePreviousThumbnail($previousThumbnails)
    {
        foreach ($previousThumbnails ?? [] as $thumbnail) {
            if (array_key_exists('file', $thumbnail) && array_key_exists('id', $thumbnail) && $thumbnail['file'] != null) {
                $media = Media::find($thumbnail['id']);

                MediaRepository::updateByRequest(
                    $thumbnail['file'],
                    'products',
                    'image',
                    $media
                );
            }
        }
    }
}
