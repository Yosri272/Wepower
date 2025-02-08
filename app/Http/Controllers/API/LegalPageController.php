<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\LegalPage;

class LegalPageController extends Controller
{
    /**
     * Get a legal page by its slug.
     *
     * @param  string  $slug  The slug of the legal page
     */
    public function index($slug)
    {
        $page = LegalPage::where('slug', $slug)->first();

        return $this->json('Legal Page', [
            'content' => [
                'title' => $page?->title,
                'description' => $page?->description,
            ],
        ]);
    }

    /**
     * get contact us page.
     */
    public function contactUs()
    {
        $contact = ContactUs::first();

        return $this->json('Contact Us', [
            'phone' => $contact?->phone,
            'email' => $contact?->email,
            'whatsapp' => $contact?->whatsapp,
            'messenger' => $contact?->messenger,
        ]);
    }
}
