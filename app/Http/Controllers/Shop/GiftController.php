<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopGiftRequest;
use App\Models\Gift;
use App\Repositories\GiftRepository;
use Illuminate\Support\Facades\Storage;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = auth()->user()->shop->gifts()->paginate(20);

        return view('shop.gift.index', compact('gifts'));
    }

    public function create()
    {
        return view('shop.gift.create');
    }

    public function store(ShopGiftRequest $request)
    {
        GiftRepository::storeByRequest($request);

        return to_route('shop.gift.index')->withSuccess(__('Gift created successfully'));
    }

    public function edit(Gift $gift)
    {
        return view('shop.gift.edit', compact('gift'));
    }

    public function update(ShopGiftRequest $request, Gift $gift)
    {
        GiftRepository::updateByRequest($request, $gift);

        return to_route('shop.gift.index')->withSuccess(__('Gift updated successfully'));
    }

    public function destroy(Gift $gift)
    {
        if ($gift->orders()->exists() || $gift->carts()->exists()) {
            return to_route('shop.gift.index')->withError(__('Gift cannot be deleted! Because it has orders or carts'));
        }

        $media = $gift->media;

        $gift->delete();

        if ($media && Storage::exists($media->src)) {
            Storage::delete($media->src);
            $media->delete();
        }

        return to_route('shop.gift.index')->withSuccess(__('Gift deleted successfully'));
    }

    public function statusToggle(Gift $gift)
    {
        $gift->update(['is_active' => ! $gift->is_active]);

        return to_route('shop.gift.index')->withSuccess(__('Status updated successfully'));
    }
}
