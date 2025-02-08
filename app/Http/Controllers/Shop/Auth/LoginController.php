<?php

namespace App\Http\Controllers\Shop\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the application login page.
     */
    public function index()
    {
        return view('shop.auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(AdminLoginRequest $request)
    {
        // check credentials
        $user = $this->checkCredentials($request);

        // check user is active
        if ($user && $user->is_active) {

            // login the user
            Auth::login($user);

            // redirect to dashboard
            return to_route('shop.dashboard')->withSuccess('Login successfully');
        } elseif ($user && ! $user->is_active) {
            return back()->withError('Your account is deactivated. Please contact the admin');
        }

        // redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials is invalid.',
            'password' => 'The provided credentials is invalid.',
        ]);
    }

    /**
     * Check user exists or not and check password.
     */
    private function checkCredentials(AdminLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return false;
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        // logout
        auth()->logout();

        // invalidate session
        $request->session()->invalidate();

        // regenerate session
        $request->session()->regenerateToken();

        return to_route('shop.login')->withSuccess('Logout successfully');
    }
}
