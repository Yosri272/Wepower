<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::role(Roles::CUSTOMER->value)->paginate(10);

        return view('admin.customer.index', compact('customers'));
    }
}
