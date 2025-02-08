<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ShopProfileRequest;
use App\Models\User;
use App\Repositories\ShopRepository;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * show profile.
     */
    public function index()
    {
        $shop = auth()->user()->shop;

        return view('shop.profile.index', compact('shop'));
    }

    /**
     * edit profile
     */
    public function edit()
    {
        $shop = auth()->user()->shop;

        return view('shop.profile.edit', compact('shop'));
    }

    /**
     * update profile
     */
    public function update(ShopProfileRequest $request)
    {
        $shop = auth()->user()->shop;

        ShopRepository::updateByRequest($shop, $request);

        return to_route('shop.profile.index')->withSuccess(__('Profile updated successfully'));
    }

    /**
     * show change password form
     */
    public function changePassword()
    {
        return view('shop.profile.change-password');
    }

    /**
     * change password
     *
     * @model User $user
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = User::find(auth()->id());
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withError(__('You have entered wrong password'));
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->withSuccess(__('password change successfully'));
    }
}
