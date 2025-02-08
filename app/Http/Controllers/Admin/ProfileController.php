<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.profile.index');
    }

    /**
     * edit profile
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {
        $user = auth()->user();

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * update profile
     */
    public function update(ProfileUpdateRequest $request)
    {
        UserRepository::updateByRequest($request, auth()->user());

        return to_route('admin.profile.index')->withSuccess(__('Profile updated successfully'));
    }

    /**
     * show change password form
     */
    public function changePassword()
    {
        return view('admin.profile.change-password');
    }

    /**
     * change password
     *
     * @model User $user
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withError(__('You have entered wrong password'));
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->withSuccess(__('password change successfully'));
    }
}
