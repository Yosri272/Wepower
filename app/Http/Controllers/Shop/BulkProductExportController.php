<?php

namespace App\Http\Controllers\Shop;

use App\Exports\TemplateExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BulkProductExportController extends Controller
{
    public function index()
    {
        return view('shop.bulk-product.export');
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $type = $request->type;

        $products = auth()->user()->shop?->products;

        $exportData = collect(
            [
                [
                    'id',
                    'name',
                    'thumbnails',
                    'category',
                    'sub category',
                    'brand',
                    'colors',
                    'sizes',
                    'price',
                    'discount price',
                    'product sku',
                    'stock qty',
                    'short description',
                    'description',
                ],
            ]
        );

        foreach ($products as $product) {
            $thumbnails = [];

            if ($product->media && Storage::exists($product->media->src)) {
                $thumbnails[] = $product->media->original_name;
            }

            foreach ($product->medias as $media) {
                if (Storage::exists($media->src)) {
                    $thumbnails[] = $media->original_name;
                }
            }

            $categories = $product->categories->pluck('name')->toArray();
            $colors = $product->colors->pluck('name')->toArray();
            $sizes = $product->sizes->pluck('name')->toArray();
            $subCategories = $product->subcategories->pluck('name')->toArray();

            $exportData[] = [
                $product->id,
                $product->name,
                implode(',', $thumbnails),
                implode(',', $categories),
                implode(',', $subCategories),
                $product->brand?->name,
                implode(',', $colors),
                implode(',', $sizes),
                $product->price,
                $product->discount_price ?? 0,
                $product->code,
                $product->quantity ?? 0,
                $product->short_description,
                $product->description,
            ];
        }

        return Excel::download(new TemplateExport($exportData), 'products.xlsx');
    }

    // export for demo
    public function demoExport(Request $request)
    {

        $exportData = collect(
            [
                [
                    'id',
                    'name',
                    'thumbnails',
                    'category',
                    'sub category',
                    'brand',
                    'colors',
                    'sizes',
                    'price',
                    'discount price',
                    'product sku',
                    'stock qty',
                    'short description',
                    'description',
                ],
            ]
        );

        $thumbnails = [];

        // get first product
        $product = auth()->user()->shop?->products()?->first();

        // check if product exists
        if ($product) {

            // check if media exists
            if ($product->media && Storage::exists($product->media->src)) {
                $thumbnails[] = $product->media->original_name;
            }

            // check if media exists
            foreach ($product->medias as $media) {
                if (Storage::exists($media->src)) {
                    $thumbnails[] = $media->original_name;
                }
            }

            $categories = $product->categories->pluck('name')->toArray();
            $colors = $product->colors->pluck('name')->toArray();
            $sizes = $product->sizes->pluck('name')->toArray();
            $subCategories = $product->subcategories->pluck('name')->toArray();

            $exportData[] = [
                0,
                $product->name,
                implode(',', $thumbnails),
                implode(',', $categories),
                implode(',', $subCategories),
                $product->brand?->name,
                implode(',', $colors),
                implode(',', $sizes),
                $product->price,
                $product->discount_price ?? 0,
                $product->code,
                $product->quantity ?? 0,
                $product->short_description,
                $product->description,
            ];
        }

        return Excel::download(new TemplateExport($exportData), 'demo-template.xlsx');
    }
}
