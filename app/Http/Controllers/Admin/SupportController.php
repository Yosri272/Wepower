<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supports = Support::paginate(20);

        return view('admin.support-messages', compact('supports'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Support $support)
    {
        $support->delete();

        return back()->withSuccess(__('Support message deleted successfully'));
    }
}
