<?php

namespace App\Http\Controllers\Shop;

use App\Events\AdminProductRequestEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Media;
use App\Models\Product;
use App\Models\SubCategory;
use App\Repositories\NotificationRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display the product list.
     */
    public function index(Request $request)
    {
        // get category, brand, color and search from request
        $category = $request->category;
        $brand = $request->brand;
        $color = $request->color;
        $search = $request->search;

        // filter products based on category, brand, color and search
        $products = auth()->user()->shop?->products()->when($brand, function ($query) use ($brand) {
            return $query->where('brand_id', $brand);
        })->when($category, function ($query) use ($category) {
            return $query->whereHas('categories', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        })->when($color, function ($query) use ($color) {
            return $query->whereHas('colors', function ($query) use ($color) {
                return $query->where('color_id', $color);
            });
        })->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate(20)->withQueryString();

        // get brands, colors and categories
        $brands = auth()->user()->shop?->brands()->get();
        $colors = auth()->user()->shop?->colors()->get();
        $categories = auth()->user()->shop?->categories()->get();

        return view('shop.product.index', compact('products', 'brands', 'colors', 'categories'));
    }

    /**
     * Display the product details.
     */
    public function show(Product $product)
    {
        return view('shop.product.show', compact('product'));
    }

    /**
     * crete new product.
     */
    public function create()
    {
        // get brands, colors and categories
        $brands = auth()->user()->shop?->brands()->isActive()->get();
        $colors = auth()->user()->shop?->colors()->isActive()->get();
        $categories = auth()->user()->shop?->categories()->active()->get();
        $units = auth()->user()->shop?->units()->isActive()->get();
        $sizes = auth()->user()->shop?->sizes()->isActive()->get();

        return view('shop.product.create', compact('brands', 'colors', 'categories', 'units', 'sizes'));
    }

    /**
     * store new product.
     */
    public function store(ProductRequest $request)
    {
        ProductRepository::storeByRequest($request);

        $isRootUser = auth()->user()->hasRole('root');

        // admin notification message
        if (! $isRootUser) {
            $message = 'New product Created Request';
            try {
                AdminProductRequestEvent::dispatch($message);
            } catch (\Throwable $th) {
            }

            $data = (object) [
                'title' => $message,
                'content' => 'New product Created Request from '.auth()->user()->shop->name,
                'url' => '/admin/products?status=0',
                'icon' => 'bi-shop',
                'type' => 'success',
            ];
            // store notification
            NotificationRepository::storeByRequest($data);
        }

        return to_route('shop.product.index')->withSuccess(__('Product created successfully!'));
    }

    /**
     * Display the product edit form.
     */
    public function edit(Product $product)
    {
        // get brands, colors, units, sizes and categories
        $brands = auth()->user()->shop?->brands()->isActive()->get();
        $colors = auth()->user()->shop?->colors()->isActive()->get();
        $categories = auth()->user()->shop?->categories()->active()->get();
        $units = auth()->user()->shop?->units()->isActive()->get();
        $sizes = auth()->user()->shop?->sizes()->isActive()->get();

        $categoryId = $product->categories()->latest('id')->first()->id;

        $subCategories = SubCategory::whereHas('categories', function ($query) use ($categoryId) {
            return $query->where('category_id', $categoryId);
        })->isActive()->get();

        return view('shop.product.edit', compact('product', 'brands', 'colors', 'categories', 'units', 'sizes', 'subCategories'));
    }

    /**
     * Update the product.
     */
    public function update(ProductRequest $request, Product $product)
    {
        ProductRepository::updateByRequest($request, $product);

        $isRootUser = auth()->user()->hasRole('root');

        // admin notification message
        if (! $isRootUser) {
            $message = 'Product Updated Request';
            try {
                AdminProductRequestEvent::dispatch($message);
            } catch (\Throwable $th) {
            }

            $data = (object) [
                'title' => $message,
                'content' => 'Product Updated Request from '.auth()->user()->shop->name,
                'url' => '/admin/products?status=1',
                'icon' => 'bi-shop',
                'type' => 'success',
            ];
            // store notification
            NotificationRepository::storeByRequest($data);
        }

        return to_route('shop.product.index')->withSuccess(__('Product updated successfully!'));
    }

    /**
     * delete thumbnail
     */
    public function thumbnailDestroy(Product $product, Media $media)
    {
        $product->medias()->detach($media->id);
        if (Storage::exists($media->src)) {
            Storage::delete($media->src);
        }

        $media->delete();

        return back()->withSuccess(__('Thumbnail deleted successfully!'));
    }

    /**
     * status toggle a product
     */
    public function statusToggle(Product $product)
    {
        if (! $product->is_approve) {
            return back()->withError(__('Sorry! Your Product is not approved yet!'));
        }

        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        return back()->withSuccess(__('Status updated successfully'));
    }

    /**
     * generate barcode
     */
    public function generateBarcode(Product $product)
    {
        if (! $product->code) {
            return back()->withError(__('Sorry! Your Product code is not generated yet!'));
        }

        $quantitys = request('qty', 4);

        return view('shop.product.barcode', compact('product', 'quantitys'));
    }
}
