<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::paginate(20);

        return view('admin.social-link.index', compact('socialLinks'));
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $socialLink->update([
            'link' => $request->link,
        ]);

        return back()->with('success', __('Social link updated successfully'));
    }
}
