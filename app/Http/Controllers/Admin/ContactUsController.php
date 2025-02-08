<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactUs = ContactUs::first();

        return view('admin.contact-us', compact('contactUs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactUs $contactUs)
    {
        ContactUs::updateOrCreate(['id' => $contactUs->id], $request->all());

        return back()->with('success', __('Contact Us Updated Successfully'));
    }
}
