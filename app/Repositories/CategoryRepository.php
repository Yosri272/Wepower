<?php

namespace App\Repositories;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Category::class;
    }

    /**
     * store a new category
     */
    public static function storeByRequest(CategoryRequest $request): Category
    {
        $thumbnail = MediaRepository::storeByRequest(
            $request->file('thumbnail'),
            'categories',
            'image'
        );

        return self::create([
            'name' => $request->name,
            'media_id' => $thumbnail->id ?? null,
            'description' => $request->description,
            'status' => true,
        ]);
    }

    /**
     * update a category
     */
    public static function updateByRequest(CategoryRequest $request, Category $category): Category
    {
        $thumbnail = $category->media;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = MediaRepository::updateByRequest(
                $request->file('thumbnail'),
                'categories',
                'image',
                $thumbnail
            );
        }
        $category->update([
            'name' => $request->name,
            'media_id' => $thumbnail->id ?? null,
            'description' => $request->description,
        ]);

        return $category;
    }
}
