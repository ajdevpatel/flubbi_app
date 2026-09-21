<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function backendLogin(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $request->merge(["email" => $request->input("loginKey")]);
            $request->merge(["status" => "1"]);
            $request->merge(["deleted" => "0"]);

            $credentials = $request->validate([
                "email" => "required|email",
                "password" => "required",
                "status" => "required",
            ]);
            $user_data = collect(DB::table("users")->select("users.id")->where("users.email", $request->input("loginKey"))
                ->whereIn("users.role", (array) config("web.webapp.admin_roles", [1]))
                ->whereNull("users.deleted_at")->first())->toArray();

            if ([] == $user_data) {
                return response()->json([
                    "errors" => [
                        "message" => [
                            "Oops! You have entered invalid credentials."
                        ]
                    ]
                ], 422);
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return  response()->json([
                    "is_url" => route("_backendDashboardIndex"),
                    "message" => "You have successfully logged in!"
                ], 200);
            }

            return response()->json([
                "errors" => [
                    "message" => [
                        "Oops! You have entered invalid credentials."
                    ]
                ]
            ], 422);
        }
        return view("backend.auth");
    }

    public function backendLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("_backendLogin")->withSuccess("You have logged out successfully!");
    }

    #######################################################################

    public function frontendLogin(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $request->merge(["email" => $request->input("loginKey")]);
            $request->merge(["status" => "1"]);
            $request->merge(["deleted" => "0"]);

            $credentials = $request->validate([
                "email" => "required|email",
                "password" => "required",
                "status" => "required",
                "deleted" => "required",
            ]);
            $user_data = collect(DB::table("users")->select("users.id")->where("users.email", $request->input("loginKey"))
                ->where("users.role", "2")->first())->toArray();

            if ([] == $user_data) {
                return response()->json([
                    "errors" => [
                        "message" => [
                            "Oops! You have entered invalid credentials."
                        ]
                    ]
                ], 422);
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return  response()->json([
                    "next_step" => route("_userDashboardIndex"),
                    "message" => "You have successfully logged in!."
                ], 200);
            }

            return response()->json([
                "errors" => [
                    "message" => [
                        "Oops! You have entered invalid credentials."
                    ]
                ]
            ], 422);
        }

        if (Auth::check()) {
            if (4 != Auth::user()->role) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            return redirect()->route("_userDashboardIndex");
        }

        $request->merge([
            "is_menu_show" => 1,
            "is_footer_show" => 1,
            "header_class" => 1,
        ]);

        return view("frontend.loginIndex");
    }

    public function forgotpasswordIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $credentials = $request->validate([
                "loginKey" => "required|email",
            ]);

            $user_data = collect(DB::table('users')->where([
                "email" => $request->loginKey,
                "status" => "1",
                "deleted" => "0",
                "role" => "4",
            ])->first())->toArray();

            if ([] != $user_data) {
                $g_password = $this->generatePassword(8);
                DB::table("users")->where("id", $user_data["id"])->update([
                    "password" => Hash::make($g_password),
                ]);

                $in_data = [
                    "subject" => "Reset your password",
                    "body_text" => "<p style='margin: 0 0 0.2rem 0;line-height:1;font-size:1rem;font-weight:600'>Hi " . $user_data["name"] . ",</p>
                    <p style='margin:0 0 0 0.5rem;line-height:1.3;font-size:0.9rem;padding-top: 0.6rem;'>
                    Your password has been reset :
                    </p>
                    <p style='margin:0 0 0 0.5rem;line-height:1.3;font-size:0.9rem;padding-top: 0.6rem;'>
                    <b>New Password : " . $g_password . "</b>
                    </p>
                    <p style='margin:0 0 0 0.5rem;line-height:1.3;font-size:0.9rem;padding-top: 0.6rem;'>
                    Please don't share with anyone
                    </p>",
                ];
                $this->sendMail($request->loginKey, $in_data);

                return response()->json([
                    "message" => "Your password is sent to your registered email ID, please check your mail",
                    "next_step" => route("_frontendLogin"),
                ], 200);
            }

            return response()->json([
                "errors" => [
                    "message" => ["Please enter a valid E-Mail"]
                ]
            ], 422);
        }
        if (Auth::check()) {
            if (4 != Auth::user()->role) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            return redirect()->route("_userDashboardIndex");
        }

        $request->merge([
            "is_menu_show" => 1,
            "is_footer_show" => 1,
            "header_class" => 1,
        ]);

        return view("frontend.forgotpasswordIndex");
    }

    public function frontendLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("_frontendLogin")->withSuccess("You have logged out successfully!");
    }
}
