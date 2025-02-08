<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportRequest;
use App\Repositories\SupportRepository;

class SupportController extends Controller
{
    /**
     * Store a support request and return a JSON response.
     *
     * @param  SupportRequest  $request  The request object
     * @return JSON The JSON response with the support content
     */
    public function store(SupportRequest $request)
    {
        $support = SupportRepository::storeByRequest($request);

        return $this->json('Your message has been sent successfully', [
            'content' => $support,
        ]);
    }
}
