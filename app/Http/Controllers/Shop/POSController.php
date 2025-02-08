<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\PosApplyCouponRequest;
use App\Http\Requests\PosCartRequest;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\GeneraleSetting;
use App\Models\Order;
use App\Models\PosCartProduct;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PosCartRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;

        $categories = $shop->categories()->active()->get();
        $brands = $shop->brands()->isActive()->get();

        $customers = Customer::all();

        return view('shop.pos.index', compact('categories', 'brands', 'customers'));
    }

    public function sales()
    {
        $orders = OrderRepository::query()->withoutGlobalScopes()->where('shop_id', auth()->user()->shop->id)->where('pos_order', true)->paginate(20);

        return view('shop.pos.sales', compact('orders'));
    }

    public function draft()
    {
        $postCarts = PosCartRepository::query()->where('shop_id', auth()->user()->shop->id)->where('is_draft', true)->orderBy('created_at', 'desc')->get();

        return view('shop.pos.draft', compact('postCarts'));
    }

    public function invoice($orderId)
    {
        $order = Order::withoutGlobalScopes()->findOrFail($orderId);

        $orderCode = '#' . $order->prefix . $order->order_code;

        $qrCode = new EndroidQrCode($orderCode);
        $qrCode->setSize(100);

        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode)->getDataUri();

        $pdf = Pdf::loadView('PDF.invoice', compact('order', 'qrCodeImage'));

        return $pdf->stream('invoice-' . $order->prefix . $order->order_code . '.pdf');
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $postCart = PosCartRepository::query()->where('shop_id', auth()->user()->shop->id)->where('name', $request->name)->first();

        if (!$postCart) {
            return $this->json(__('Sorry shop cart is empty'), [], 422);
        }
        if ($postCart->products->count() == 0) {
            return $this->json(__('Please select a products.'), [], 422);
        }

        $order = PosCartRepository::storeOrder($postCart, $request);

        $message = __('Sale created successfully');
        $request->order_type == 'draft' ? $message = __('Sale draft created successfully') : '';

        $invoiceUrl = null;
        if (is_object($order)) {
            $invoiceUrl = route('shop.pos.invoice', $order->id);
        }

        return $this->json($message, [
            'invoice_url' => $invoiceUrl,
        ], 200);
    }

    public function storeCustomer(Request $request)
    {
        $request['is_active'] = 1;

        $user = UserRepository::registerNewUser($request);

        $user->assignRole(Roles::CUSTOMER->value);

        CustomerRepository::storeByRequest($user);

        return $this->json(__('Created Successfully'), [
            'user' => UserResource::make($user),
        ], 200);
    }

    public function getProduct(Request $request)
    {
        $brand = $request->brand;
        $category = $request->category;
        $search = $request->search;

        $generaleSetting = GeneraleSetting::first();

        $products = auth()->user()->shop->products()->when($brand, function ($query) use ($brand) {
            return $query->where('brand_id', $brand);
        })->when($category, function ($product) use ($category) {
            return $product->whereHas('categories', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        })->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->isActive()->get();

        return $this->json('Products', [
            'currency' => $generaleSetting?->currency ?? '$',
            'currency_position' => $generaleSetting?->currency_position ?? 'prefix',
            'products' => ProductResource::collection($products),
        ]);
    }

    public function addToCart(PosCartRequest $request)
    {
        $product = ProductRepository::find($request->product_id);

        if ($product->quantity < $request->quantity) {
            return $this->json(__('Sorry! product cart quantity is limited. No more stock'), [], 422);
        }

        PosCartRepository::storeByRequest($request, $product);

        return $this->json(__('Product added successfully'), [], 200);
    }

    public function getCart(Request $request)
    {
        $postCart = PosCartRepository::getLatestCart($request);

        return $this->json('pos cart', [
            'subtotal' => $postCart?->subtotal ?? 0,
            'discount' => $postCart?->discount ?? 0,
            'total' => $postCart?->total ?? 0,
            'user' => $postCart?->user ? UserResource::make($postCart->user) : null,
            'name' => $postCart?->name ?? null,
            'coupon_code' => $postCart?->coupon?->code ??  null,
            'products' => $postCart?->products ? OrderProductResource::collection($postCart?->products) : [],
        ]);
    }

    public function updateCart(PosCartRequest $request)
    {
        $product = ProductRepository::find($request->product_id);

        if ($product->quantity < $request->quantity) {
            return $this->json(__('Sorry! product cart quantity is limited. No more stock'), [], 422);
        }

        $postCartProduct = PosCartProduct::find($request->pos_cart_id);
        if (!$postCartProduct) {
            return $this->json(__('Sorry this product is not in cart'), [], 422);
        }

        PosCartRepository::updateByRequest($request, $postCartProduct);
    }

    public function removeCart(Request $request)
    {
        $postCart = PosCartRepository::query()->where('shop_id', auth()->user()->shop->id)->where('name', $request->name)->first();

        PosCartRepository::destroyProduct($request, $postCart);

        return $this->json(__('Product deleted successfully'), [], 200);
    }

    public function applyCoupon(PosApplyCouponRequest $request)
    {
        $code = $request->coupon_code;

        $coupon = auth()->user()->shop->coupons()->where('code', $code)->active()->IsValid()->first();

        if (!$coupon) {
            return $this->json(__('Invalid coupon code'), [], 422);
        }

        $postCart = PosCartRepository::query()->where('shop_id', auth()->user()->shop->id)->where('name', $request->name)->first();

        if ($postCart) {
            $postCart = PosCartRepository::applyCoupon($request, $coupon, $postCart);
        }

        return $this->json(__('Coupon applied'), [], 200);
    }

    public function removeCoupon(Request $request)
    {
        $postCart = PosCartRepository::query()->where('shop_id', auth()->user()->shop->id)->where('name', $request->name)->first();

        if ($postCart) {
            $postCart = PosCartRepository::removeCoupon($postCart);

            return $this->json(__('Coupon removed'), [], 200);
        }

        return $this->json(__('Coupon not found'), [], 422);
    }
}
