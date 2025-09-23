<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   protected function authenticated($request, $user)
{
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'cashier') {
        return redirect()->route('cashier.dashboard');
    } elseif ($user->role === 'helper') {
        return redirect()->route('helper.dashboard');
    } elseif ($user->role === 'inventory') {
        return redirect()->route('inventory.dashboard');
    }

    // fallback if no role
    return redirect('/login')->withErrors('Role not assigned.');
}

}
