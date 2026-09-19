<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class FendUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !Auth::check() || !Auth::user() || null === Auth::id()) {
            // User is not authenticated, redirect to the login page
            if ($request->ajax()) {
                return response()->json([
                    "messages" => "Please log in to access this page.",
                    "next_step" => route("_frontendLogin"),
                ]);
            }
            return redirect()->route("_frontendLogin")->with("error", "Please log in to access this page.");
        }

        if (!in_array(Auth::user()->role, [4])) {
            if ($request->ajax()) {
                return response()->json([
                    "messages" => "Please log in to access this page.",
                    "next_step" => route("_frontendLogin"),
                ]);
            }
            return redirect()->route("_frontendLogin")->with("error", "Please log in to access this page.");
        }

        #Add UUID Param
        if ($request->route()->parameter("key")) {
            $request->merge(["uuid" => $request->route()->parameter("key")]);
        }

        #Check Request Method
        if ("POST" === $request->method()) {
            return $next($request);
        }

        /*
        $menu_route = explode("/", Route::current()->uri);
        $request->merge([
            "menu_route" => (array_key_exists("1", $menu_route)) ? $menu_route[1] : $menu_route,
        ]);
        */

        return $next($request);
    }
}