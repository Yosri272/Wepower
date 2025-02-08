<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductApproveEvent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\NotificationRepository;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $shop = $request->shop;
        $approve = $request->approve;

        $products = Product::when($status == '1', function ($query) {
            return $query->where('is_approve', false)->where('is_new', false);
        })->when($status == '0', function ($query) {
            return $query->where('is_approve', false)->where('is_new', true);
        })->when($approve, function ($query) {
            return $query->where('is_approve', true)->where('is_active', true);
        })->when($shop, function ($query) use ($shop) {
            return $query->where('shop_id', $shop);
        })->paginate(20);

        $shops = ShopRepository::query()->isActive()->get();

        return view('admin.product.index', compact('products', 'shops'));
    }

    public function show(Product $product)
    {

        return view('admin.product.show', compact('product'));
    }

    /**
     * Approve the product.
     */
    public function approve(Product $product)
    {
        // update product
        $product->update([
            'is_approve' => true,
            'is_new' => false,
            'is_active' => true,
        ]);

        // admin notification message
        $message = 'Product Approved';
        try {
            ProductApproveEvent::dispatch($message, $product->shop_id);
        } catch (\Throwable $th) {
        }

        $data = (object) [
            'title' => $message,
            'content' => 'Your product approved from admin',
            'url' => '/shop/product/'.$product->id.'/show',
            'icon' => 'bi-bag-check-fill',
            'type' => 'success',
            'shop_id' => $product->shop_id,
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return back()->withSuccess(__('Product approved successfully'));
    }

    public function destroy(Product $product)
    {
        $shopID = $product->shop_id;
        if ($product->media && Storage::exists($product->media->src)) {
            Storage::delete($product->media->src);
        }
        $product->media()->delete();
        $product->sizes()->delete();
        $product->colors()->delete();
        $product->reviews()->delete();
        $product->units()->delete();
        $product->categories()->detach();

        foreach ($product->medias as $media) {
            if ($media && Storage::exists($media->src)) {
                Storage::delete($media->src);
            }
            $media->delete();
        }

        $product->delete();

        // admin notification message
        $message = 'Product Deleted';
        try {
            ProductApproveEvent::dispatch($message, $shopID);
        } catch (\Throwable $th) {
        }

        $data = (object) [
            'title' => $message,
            'content' => 'Your product approved from admin',
            'url' => null,
            'icon' => 'bi-x-octagon-fill',
            'type' => 'danger',
            'shop_id' => $shopID,
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return back()->withSuccess(__('Product deleted successfully'));
    }
}
