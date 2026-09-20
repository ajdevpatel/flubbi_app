<?php

namespace App\Http\Controllers\Fend;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserPanelController extends Controller
{
    public const SESSION_KEY = "fl_service";

    public const LOAN_TYPES = [1, 2];

    public const STATUS_CLASS = [
        1 => "pending",
        2 => "review",
        3 => "approved",
        4 => "rejected",
        5 => "disbursed",
        6 => "closed",
    ];

    public const DOCUMENTS = [
        "kyc" => [
            "aadhar_front" => "Aadhaar front",
            "aadhar_back" => "Aadhaar back",
            "pan_front" => "PAN card",
            "selfie" => "Selfie",
        ],
        "salaried" => [
            "salary_slip" => "Salary slips (3 months)",
            "bank_statement" => "Bank statement (6 months)",
            "utility_bill" => "Utility bill",
            "cancel_cheque" => "Cancelled cheque",
        ],
        "self_employed" => [
            "business_proof" => "Business proof",
            "gst_return" => "GST returns",
            "income_tax_eturn" => "Income tax return",
            "bank_statement" => "Bank statement (6 months)",
            "utility_bill" => "Utility bill",
            "cancel_cheque" => "Cancelled cheque",
        ],
        "other" => [
            "other_one" => "Other document 1",
            "other_two" => "Other document 2",
            "other_three" => "Other document 3",
        ],
    ];

    public function sessionUser()
    {
        $user_id = (int) (session(self::SESSION_KEY . ".user_id") ?? 0);
        if (0 == $user_id) {
            return null;
        }
        return DB::table("users as u")
            ->leftJoin("states as s", "s.id", "=", "u.state_id")
            ->select(["u.*", "s.name as state_name"])
            ->where("u.id", $user_id)
            ->whereNull("u.deleted_at")
            ->first();
    }

    private function requireUser(Request $request): array
    {
        $user = $this->sessionUser();
        if ($user) {
            return [$user, null];
        }

        if ("GET" !== $request->method() || $request->ajax()) {
            return [null, response()->json([
                "errors" => ["message" => ["Please log in to continue."]],
                "step" => route("_userLogin"),
            ], 422)];
        }

        session()->put(self::SESSION_KEY . ".login_next", $request->fullUrl());
        return [null, redirect()->route("_userLogin")];
    }

    private function panelView(Request $request, string $view, $user, array $data = [])
    {
        $request->merge(["header_class" => 1]);

        return view("frontend.user." . $view, array_merge([
            "user" => $user,
            "unread" => DB::table("notifications")->where("user_id", $user->id)->where("is_read", 0)->count(),
            "support" => $this->supportData(),
        ], $data));
    }

    private function supportData(): array
    {
        $rows = DB::table("options")->whereIn("key", ["support_mail", "support_phone"])->pluck("value", "key");
        return [
            "mail" => $rows["support_mail"] ?? config("web.store_data.support_mail"),
            "phone" => $rows["support_phone"] ?? config("web.store_data.phone"),
        ];
    }

    private function applications($user)
    {
        return DB::table("loan_applications as la")
            ->leftJoin("loan_types as lt", "lt.id", "=", "la.loan_type_id")
            ->leftJoin("loan_status as ls", "ls.id", "=", "la.status")
            ->leftJoin("payment_transactions as pt", "pt.loan_application_id", "=", "la.id")
            ->leftJoin("self_login_banks as b", "b.id", "=", "la.self_login_bank_id")
            ->select([
                "la.*",
                "lt.label as loan_type",
                "ls.label as status_label",
                "pt.status as tz_status",
                "pt.total_amount as tz_amount",
                "b.label as bank_name",
                "b.logo as bank_logo",
            ])
            ->where("la.user_id", $user->id)
            ->whereIn("la.loan_type_id", self::LOAN_TYPES)
            ->orderByDesc("la.id")
            ->get();
    }

    public function loginIndex(Request $request, OtpService $otp_service)
    {
        $request->merge(["header_class" => 1]);

        if ("POST" === $request->method()) {
            return $this->loginPost($request, $otp_service);
        }

        if ($this->sessionUser()) {
            return redirect()->route("_userDashboard");
        }

        if ($request->boolean("change")) {
            session()->forget(self::SESSION_KEY . ".login_otp");
            return redirect()->route("_userLogin");
        }

        $otp = session(self::SESSION_KEY . ".login_otp", []);
        $cooldown = 0;
        if (!empty($otp["sent_at"])) {
            $elapsed = (int) Carbon::parse($otp["sent_at"])->diffInSeconds(now());
            $cooldown = max(0, (int) config("web.sms.otp.cooldown_seconds", 60) - $elapsed);
        }

        return view("frontend.user.loginIndex", [
            "otp_sent" => !empty($otp["phone"]),
            "phone" => $otp["phone"] ?? "",
            "cooldown" => $cooldown,
        ]);
    }

    public function loginSendOtp(Request $request, OtpService $otp_service)
    {
        $validator = Validator::make($request->all(), [
            "phone" => ["required", "digits:10", "regex:/^[6-9][0-9]{9}$/"],
        ], [
            "phone.required" => "Please enter your mobile number.",
            "phone.digits" => "Mobile number must be exactly 10 digits.",
            "phone.regex" => "Please enter a valid Indian mobile number.",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => ["message" => [$validator->errors()->first()]]], 422);
        }

        $phone = (string) $request->input("phone");
        $user = DB::table("users")->where("phone", $phone)->whereNull("deleted_at")->first();

        if (!$user) {
            return response()->json([
                "errors" => ["message" => ["No account found for this mobile number. Check your loan eligibility to create one."]],
            ], 422);
        }
        if (2 == (int) $user->status) {
            return response()->json(["errors" => ["message" => ["This account is blocked. Please contact support."]]], 422);
        }

        $sent = $otp_service->issue($phone);
        if (true !== $sent["status"]) {
            return response()->json(["errors" => ["message" => [$sent["message"]]]], 422);
        }

        session()->put(self::SESSION_KEY . ".login_otp", [
            "phone" => $phone,
            "sent_at" => now()->toDateTimeString(),
        ]);

        return response()->json([
            "message" => $sent["message"],
            "otp_sent" => true,
            "cooldown" => $sent["cooldown"] ?? (int) config("web.sms.otp.cooldown_seconds", 60),
            "cooldown_target" => "#fl-resend-btn",
            "show" => "#fl-otp-wrap",
            "hide" => "#fl-send-wrap",
            "readonly" => "#phone",
            "focus" => "#otp",
        ], 200);
    }

    private function loginPost(Request $request, OtpService $otp_service)
    {
        $pending = session(self::SESSION_KEY . ".login_otp", []);
        $phone = (string) ($pending["phone"] ?? "");
        if ("" === $phone) {
            return response()->json(["errors" => ["message" => ["Please request an OTP first."]]], 422);
        }

        $validator = Validator::make($request->all(), [
            "otp" => ["required", "digits:6"],
        ], [
            "otp.required" => "Please enter the 6 digit OTP.",
            "otp.digits" => "OTP must be exactly 6 digits.",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => ["message" => [$validator->errors()->first()]]], 422);
        }

        $check = $otp_service->verify($phone, (string) $request->input("otp"));
        if (true !== $check["status"]) {
            $max_attempts = (int) config("web.sms.otp.max_attempts", 5);
            $attempts = (int) ($pending["attempts"] ?? 0) + 1;

            if ($attempts >= $max_attempts) {
                DB::table("otp_logs")->where("phone", $phone)->where("is_used", 0)->update(["is_used" => 1, "updated_at" => now()]);
                session()->forget(self::SESSION_KEY . ".login_otp");

                return response()->json([
                    "errors" => ["message" => ["Too many wrong attempts. Please request a new OTP."]],
                    "step" => route("_userLogin"),
                ], 422);
            }

            session()->put(self::SESSION_KEY . ".login_otp.attempts", $attempts);
            $left = $max_attempts - $attempts;

            return response()->json([
                "errors" => ["message" => [$check["message"] . " " . $left . " attempt" . (1 == $left ? "" : "s") . " left."]],
            ], 422);
        }

        $user = DB::table("users")->where("phone", $phone)->whereNull("deleted_at")->first();
        if (!$user) {
            return response()->json(["errors" => ["message" => ["No account found for this mobile number."]]], 422);
        }
        if (2 == (int) $user->status) {
            return response()->json(["errors" => ["message" => ["This account is blocked. Please contact support."]]], 422);
        }

        DB::table("users")->where("id", $user->id)->update([
            "mobile_verified_at" => now(),
            "status" => 0 == (int) $user->status ? 1 : (int) $user->status,
            "updated_at" => now(),
        ]);

        $request->session()->regenerate();
        session()->forget(self::SESSION_KEY . ".login_otp");
        session()->put(self::SESSION_KEY . ".user_id", (int) $user->id);

        $next = (string) (session()->pull(self::SESSION_KEY . ".login_next") ?: route("_userDashboard"));

        return response()->json([
            "message" => "Welcome back" . ($user->name ? ", " . $user->name : "") . "!",
            "step" => $next,
        ], 200);
    }

    public function logoutIndex(Request $request)
    {
        session()->forget(self::SESSION_KEY);
        $request->session()->regenerate();
        return redirect()->route("_homeIndex")->with("success", "You have been logged out.");
    }

    public function dashboardIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        $apps = $this->applications($user);

        return $this->panelView($request, "dashboardIndex", $user, [
            "applications" => $apps,
            "latest" => $apps->first(),
            "stats" => [
                "total" => $apps->count(),
                "active" => $apps->whereIn("status", [1, 2, 3])->count(),
                "paid" => $apps->where("payment_status", 1)->count(),
                "disbursed" => $apps->where("status", 5)->count(),
            ],
            "notifications" => DB::table("notifications")->where("user_id", $user->id)->orderByDesc("id")->limit(3)->get(),
        ]);
    }

    public function applicationsIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        return $this->panelView($request, "applicationsIndex", $user, [
            "applications" => $this->applications($user),
        ]);
    }

    public function applicationShow(Request $request, string $application)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        $loan = $this->applications($user)->firstWhere("application_no", $application);
        if (!$loan) {
            return redirect()->route("_userApplications");
        }

        $detail = DB::table("loan_applications as la")
            ->leftJoin("loan_purposes as lp", "lp.id", "=", "la.loan_purpose_id")
            ->leftJoin("cibil_scores as cs", "cs.id", "=", "la.cibil_score")
            ->select(["la.*", "lp.label as purpose", "cs.label as cibil_label"])
            ->where("la.id", $loan->id)
            ->first();

        $bank_link = "";
        if (!empty($loan->self_login_bank_id)) {
            $bank = DB::table("self_login_banks")->where("id", $loan->self_login_bank_id)->first();
            $bank_link = $bank ? trim((string) (2 == $loan->loan_type_id ? $bank->bl_link : $bank->pl_link)) : "";
        }

        $history = DB::table("application_history as h")
            ->leftJoin("loan_status as ls", "ls.id", "=", "h.status_id")
            ->select(["h.*", "ls.label"])
            ->where("h.application_id", $loan->id)
            ->orderBy("h.id")
            ->get();

        return $this->panelView($request, "applicationShowIndex", $user, [
            "loan" => $loan,
            "detail" => $detail,
            "bank_link" => $bank_link,
            "history" => $history,
            "docs" => $this->documentStatus($loan),
        ]);
    }

    public function documentsIndex(Request $request, string $application = "")
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Document upload is not available yet."]]], 422);
        }

        $apps = $this->applications($user);
        $loan = $apps->firstWhere("application_no", $application) ?? $apps->first();

        return $this->panelView($request, "documentsIndex", $user, [
            "applications" => $apps,
            "loan" => $loan,
            "docs" => $loan ? $this->documentStatus($loan) : [],
        ]);
    }

    private function documentStatus($loan): array
    {
        $row = DB::table("loan_documents")->where("loan_application_id", $loan->id)->first();
        $income = "self_employed" === ($loan->employment_type ?? "salaried") ? "self_employed" : "salaried";
        $groups = [
            "kyc" => self::DOCUMENTS["kyc"],
            $income => self::DOCUMENTS[$income],
            "other" => self::DOCUMENTS["other"],
        ];

        $out = [];
        foreach ($groups as $group => $items) {
            foreach ($items as $key => $label) {
                $file = array_key_exists($key, self::DOCUMENTS["kyc"]) ? ($loan->$key ?? null) : ($row->$key ?? null);
                $out[$group][] = [
                    "key" => $key,
                    "label" => $label,
                    "file" => $file,
                    "url" => $file ? asset("uploads/" . $file) : "",
                    "status" => $file ? ("rejected" === ($row->status ?? "") ? "rejected" : "uploaded") : "missing",
                ];
            }
        }
        return $out;
    }

    public function profileIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Profile update is not available yet."]]], 422);
        }

        return $this->panelView($request, "profileIndex", $user, [
            "states" => DB::table("states")->select(["id", "name"])->where("status", 0)->orderBy("name")->get(),
        ]);
    }

    public function supportIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Support tickets are not available yet."]]], 422);
        }

        return $this->panelView($request, "supportIndex", $user, [
            "reasons" => DB::table("support_reasons")->select(["id", "label"])->where("status", 1)->orderBy("id")->get(),
            "tickets" => DB::table("support_tickets as t")
                ->leftJoin("support_reasons as r", "r.id", "=", "t.reason_id")
                ->select(["t.*", "r.label as reason"])
                ->where("t.user_id", $user->id)
                ->orderByDesc("t.id")
                ->get(),
        ]);
    }

    public function notificationsIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        return $this->panelView($request, "notificationsIndex", $user, [
            "notifications" => DB::table("notifications")->where("user_id", $user->id)->orderByDesc("id")->limit(50)->get(),
        ]);
    }

    public function transactionsIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        return $this->panelView($request, "transactionsIndex", $user, [
            "transactions" => DB::table("payment_transactions as pt")
                ->leftJoin("loan_applications as la", "la.id", "=", "pt.loan_application_id")
                ->leftJoin("loan_types as lt", "lt.id", "=", "la.loan_type_id")
                ->select(["pt.*", "la.application_no", "la.login_type", "lt.label as loan_type"])
                ->where("pt.user_id", $user->id)
                ->orderByDesc("pt.id")
                ->get(),
        ]);
    }

    public function deleteAccountIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Account deletion is not available yet."]]], 422);
        }

        return $this->panelView($request, "deleteAccountIndex", $user);
    }
}
