<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class FendAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        #Add UUID Param
        if ($request->route()->parameter("key")) {
            $request->merge(["uuid" => $request->route()->parameter("key")]);
        }

        $menu_route = explode("/", Route::current()->uri);
        $request->merge([
            "menu_route" => (array_key_exists("1", $menu_route)) ? $menu_route[1] : $menu_route,
        ]);

        $request->merge([
            "header_class" => 0,
            "is_menu_show" => 1,
            "is_footer_show" => 1,
            "is_sidebar_menu_show" => 1,
            "is_footer_chat_enquiry_show" => 1,
        ]);

        $panel_user_id = (int) (session("fl_service.user_id") ?? 0);
        $panel_user = 0 < $panel_user_id
            ? DB::table("users")->select(["id", "name", "phone"])->where("id", $panel_user_id)->whereNull("deleted_at")->first()
            : null;
        $request->merge([
            "panel_user_id" => $panel_user->id ?? 0,
            "panel_user_name" => $panel_user->name ?? "",
        ]);

        #############################################

        $site_op = collect(DB::table("web_options")->select([
            "web_options.op_key",
            "web_options.op_value"
        ])->where("web_options.status", "0")->where("web_options.deleted", "0")->get())->toArray();

        if ([] != $site_op) {
            foreach ($site_op as $k => $v) {
                $request->merge([
                    $v->op_key => $v->op_value
                ]);
            }
        }

        return $next($request);
    }
}
