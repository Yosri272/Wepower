<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MailConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.mail-config');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $this->setEnv('MAIL_MAILER', $request->mailer);
            $this->setEnv('MAIL_HOST', $request->host);
            $this->setEnv('MAIL_PORT', $request->port);
            $this->setEnv('MAIL_USERNAME', $request->username);
            $this->setEnv('MAIL_PASSWORD', $request->password);
            $this->setEnv('MAIL_ENCRYPTION', $request->encryption);
            $this->setEnv('MAIL_FROM_ADDRESS', $request->from_address);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return back()->with('success', __('Mail configuration updated successfully.'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
