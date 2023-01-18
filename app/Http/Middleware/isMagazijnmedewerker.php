<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isMagazijnmedewerker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->currentAccessToken()->can('magazijnmedewerker')) {
            return response([
                "message" => "Unautherized."
            ], 401);
        }

        return $next($request);
    }
}
