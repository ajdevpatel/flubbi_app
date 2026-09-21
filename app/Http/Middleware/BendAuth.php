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
        if (!Auth::check() || !Auth::user() || null === Auth::id()) {
            return $this->reject($request);
        }

        $admin = DB::table("users")
            ->select(["id", "role", "status"])
            ->where("id", Auth::id())
            ->whereIn("role", (array) config("web.webapp.admin_roles", [1]))
            ->where("status", 1)
            ->whereNull("deleted_at")
            ->first();

        if (null === $admin) {
            Auth::logout();

            return $this->reject($request);
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
    }

    private function reject(Request $request): Response
    {
        if ($request->ajax()) {
            return response()->json([
                "messages" => "Please log in to access this page.",
                "is_url" => route("_backendLogout"),
            ]);
        }

        return redirect()->route("_backendLogout")->with("error", "Please log in to access this page.");
    }
}
