<?php

namespace App\Http\Middleware;

use Closure;

class IsRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = \Auth::user();

        if ($user->role == $role) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Your user is not authorized.']);
        }
    }
}
