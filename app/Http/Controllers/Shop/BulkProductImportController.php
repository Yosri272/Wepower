<?php

namespace App\Http\Controllers\Shop;

use App\Exports\TemplateExport;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BulkProductImportController extends Controller
{
    public function index()
    {
        $galleries = auth()->user()->shop->galleries()->latest('id')->get();

        return view('shop.bulk-product.import', compact('galleries'));
    }

    public function formatExport(Request $request)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $quantity = $request->quantity;

        $shop = auth()->user()->shop;

        $galleries = $shop->galleries()->latest('id')->get();
        $categories = $shop->categories()->active()->get();
        $brands = $shop->brands()->isActive()->get();
        $colors = $shop->colors()->isActive()->get();
        $sizes = $shop->sizes()->isActive()->get();

        return view('shop.bulk-product.exportFormat', compact('galleries', 'categories', 'brands', 'colors', 'sizes', 'quantity'));
    }

    public function export(Request $request)
    {
        $quantity = $request->quantity;

        $exports = $request->export;

        $xlsxData = collect([]);

        for ($i = 1; $i <= $quantity; $i++) {

            if ($exports && array_key_exists($i, $exports)) {

                $requestData = $exports[$i];

                $name = $requestData['name'];
                $thumbnail = null;
                $category = null;
                $subCategory = null;
                $brand = null;
                $color = null;
                $size = null;
                $price = $requestData['price'];
                $discount_price = $requestData['discount_price'];
                $product_sku = $requestData['product_sku'];
                $stock_qty = $requestData['stock_qty'];
                $short_description = $requestData['short_description'];
                $description = $requestData['description'];

                if (array_key_exists('gallery_images', $requestData)) {
                    $thumbnail = implode(',', $requestData['gallery_images']);
                }

                if (array_key_exists('category', $requestData)) {
                    $value = array_values($requestData['category']);
                    $category = implode(',', $value);
                }

                if (array_key_exists('sub_category', $requestData)) {
                    $value = array_values($requestData['sub_category']);
                    $subCategory = implode(',', $value);
                }

                if (array_key_exists('brands', $requestData)) {
                    $value = array_values($requestData['brands']);
                    $brand = implode(',', $value);
                }

                if (array_key_exists('colors', $requestData)) {
                    $value = array_values($requestData['colors']);
                    $color = implode(',', $value);
                }

                if (array_key_exists('sizes', $requestData)) {
                    $value = array_values($requestData['sizes']);
                    $size = implode(',', $value);
                }

                if ($name || $thumbnail || $category || $brand || $color || $size || $price || $discount_price || $stock_qty) {

                    $xlsxData[] = [
                        $name ?? 'Product Name',
                        $thumbnail,
                        $category,
                        $brand,
                        $color,
                        $size,
                        $price ?? 'Price',
                        $discount_price ?? 'Discount Price',
                        $product_sku,
                        $stock_qty ?? 'Stock Quantity',
                        $short_description ?? 'Short Description',
                        $description ?? 'Description',
                    ];
                }
            }
        }

        if (! $xlsxData->count()) {
            return back()->with('error', 'No data to export!');
        }

        return Excel::download(new TemplateExport($xlsxData), 'bulk-product-template.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx']);

        $file = $request->file('file');

        $folders = $request->folder;

        $path = $file->getRealPath();

        $data = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

        $rows = $data->getActiveSheet()->toArray();
        $rows = array_slice($rows, 1);

        if (count($rows) <= 0) {
            return back()->with('error', __('Sorry! File is empty.'));
        }

        $total = ProductRepository::bulkItemStore($rows, $folders);

        return back()->with([
            'success' =>  __('Bulk Product Added Successfully'),
            'total' => $total,
        ]);
    }
}
