<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            if (Auth::user()->status === 'approved' && !Auth::user()->profile_completed) {
                // Allow access to profile setup route
                if ($request->route()->getName() === 'profile.setup' || $request->route()->getName() === 'profile.setup.store') {
                    return $next($request);
                }
                
                // Allow access to logout
                if ($request->route()->getName() === 'logout') {
                    return $next($request);
                }
                
                // Redirect to profile setup
                return redirect()->route('profile.setup');
            }
        }
        
        return $next($request);
    }
}
