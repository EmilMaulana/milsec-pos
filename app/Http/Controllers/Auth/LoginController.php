<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override method untuk mencatat aktivitas setelah login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return void
     */
    protected function authenticated($request, $user)
    {
        // Catat aktivitas login
        logActivity('User dengan email ' . $user->email . ' berhasil login.');

        // Tambahkan pesan ke session
        session()->flash('success', 'You are logged in!');
    }
}

