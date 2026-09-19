<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class BendAuth
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
                    "is_url" => route("_backendLogout"),
                ]);
            }
            return redirect()->route("_backendLogout")->with("error", "Please log in to access this page.");
        }

        if (!in_array(Auth::user()->role, [1, 2, 3])) {
            if ($request->ajax()) {
                return response()->json([
                    "messages" => "Please log in to access this page.",
                    "is_url" => route("_backendLogout"),
                ]);
            }
            return redirect()->route("_backendLogout")->with("error", "Please log in to access this page.");
        }
        date_default_timezone_set('Asia/Kolkata');

        #Add UUID Param
        if ($request->route()->parameter("key")) {
            $request->merge(["uuid" => $request->route()->parameter("key")]);
        }

        #Check Request Method
        if ("POST" === $request->method()) {
            return $next($request);
        }

        $menu_route = explode("/", Route::current()->uri);
        $request->merge([
            "menu_route" => (array_key_exists("1", $menu_route)) ? $menu_route[1] : $menu_route,
        ]);

        return $next($request);

        /*
        $app_store_data = collect(DB::table("store_info")
            ->select("store_info.*")
            ->where("store_info.id", 1)
            ->first())->toArray();

        $app_store_tagline = config("web.webapp.env.app_name");
        $app_store_logo = config("web.webapp.base_url") . "store/logo.webp";
        $app_store_favicon = config("web.webapp.base_url") . "store/favicon.webp";
        $app_store_p_logo = config("web.webapp.base_url") . "store/placeholder.webp";

        if ([] !== $app_store_data) {
            if (!empty($app_store_data["logo"]) && file_exists(public_path("uploads/store/" . $app_store_data["logo"]))) {
                $app_store_logo = config("web.webapp.base_url") . "uploads/store/" . $app_store_data["logo"];
            }
            if (!empty($app_store_data["favicon"]) && file_exists(public_path("uploads/store/" . $app_store_data["favicon"]))) {
                $app_store_favicon = config("web.webapp.base_url") . "uploads/store/" . $app_store_data["favicon"];
            }
            if (!empty($app_store_data["p_logo"]) && file_exists(public_path("uploads/store/" . $app_store_data["p_logo"]))) {
                $app_store_p_logo = config("web.webapp.base_url") . "uploads/store/" . $app_store_data["p_logo"];
            }
            if (!empty($app_store_data["tagline"])) {
                $app_store_tagline = $app_store_data["tagline"];
            }
        }

        $menu_route = explode("/", Route::current()->uri);

        $request->merge([
            "auth_name" => auth()->user()->name,
            "app_store_logo" => $app_store_logo,
            "app_store_favicon" => $app_store_favicon,
            "app_store_p_logo" => $app_store_p_logo,
            "app_store_tagline" => $app_store_tagline,
            "menu_route" => (array_key_exists("1", $menu_route)) ? $menu_route[1] : $menu_route,
        ]);

        */
    }
}