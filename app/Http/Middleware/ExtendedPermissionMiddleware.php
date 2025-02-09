<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ExtendedPermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        if(!isAdmin()) {
            $authGuard = app('auth')->guard($guard);

            if ($authGuard->guest()) {
                throw UnauthorizedException::notLoggedIn();
            }

            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);

            foreach ($permissions as $permission) {
                if ($authGuard->user()->can($permission)) {
                    return $next($request);
                }
            }

            if ($request->route()->getName() == "dashboard") {
                if (isAdmin()) {
                    return redirect('dashboard');
                } else if (auth()->user()->can('gantt-view')) {
                    return redirect('dashboard');
                } else if (auth()->user()->can('order-process-card-view')) {
                    return redirect('order-process-card');
                } else if (auth()->user()->can('order-view')) {
                    return redirect('order');
                } else if (auth()->user()->can('cutting-view')) {
                    return redirect('cutting');
                } else if (auth()->user()->can('turning-view')) {
                    return redirect('turning');
                } else if (auth()->user()->can('milling-view')) {
                    return redirect('milling');
                } else if (auth()->user()->can('other-view')) {
                    return redirect('other');
                } else if (auth()->user()->can('dispatch-view')) {
                    return redirect('dispatch');
                } else if (auth()->user()->can('role-view')) {
                    return redirect('role');
                } else if (auth()->user()->can('user-view')) {
                    return redirect('user');
                } else {
                    return redirect('profile');
                }
            }

            throw UnauthorizedException::forPermissions($permissions);
        } else {
            return $next($request);
        }
    }
}
