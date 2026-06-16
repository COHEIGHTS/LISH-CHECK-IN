<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->status === 'pending') {
                return redirect()->route('pending.approval');
            }

            if ($user->status === 'rejected') {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Your account has been rejected. Please contact the administrator.');
            }

            if ($user->status === 'suspended') {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Your account has been suspended. Please contact the administrator.');
            }
        }

        return $next($request);
    }
}
