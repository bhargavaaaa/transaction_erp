<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
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
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        if (isAdmin()) {
            return '/dashboard';
        } else if (auth()->user()->can('gantt-view')) {
            return '/dashboard';
        } else if (auth()->user()->can('order-process-card-view')) {
            return '/order-process-card';
        } else if (auth()->user()->can('order-view')) {
            return '/order';
        } else if (auth()->user()->can('cutting-view')) {
            return '/cutting';
        } else if (auth()->user()->can('turning-view')) {
            return '/turning';
        } else if (auth()->user()->can('milling-view')) {
            return '/milling';
        } else if (auth()->user()->can('other-view')) {
            return '/other';
        } else if (auth()->user()->can('dispatch-view')) {
            return '/dispatch';
        } else if (auth()->user()->can('role-view')) {
            return '/role';
        } else if (auth()->user()->can('user-view')) {
            return '/user';
        } else {
            return '/profile';
        }
    }
}
