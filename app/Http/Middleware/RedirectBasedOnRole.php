<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;

            // Redirect based on role
            if ($request->is('dashboard')) {
                if ($role === 'admin') {
                    return redirect()->route('dashboard.admin');
                } elseif ($role === 'staff') {
                    return redirect()->route('dashboard.staff');
                } elseif ($role === 'attachee') {
                    return redirect()->route('dashboard.attachee');
                }
            }
        }

        return $next($request);
    }
}
