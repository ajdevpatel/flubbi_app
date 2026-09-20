<?php

namespace App\Http\Controllers\Fend;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoanServiceController extends Controller
{
    public const DESIGN_PREVIEW = true;

    public const LIVE_STEPS = ["verify", "profile"];

    public const SERVICES = [
        "personal" => [
            "slug" => "pl-service",
            "loan_type_id" => 1,
            "label" => "Personal Loan",
            "short" => "PL",
            "default_purpose_id" => 1,
            "tagline" => "Funds for a medical emergency, wedding, travel, education or any personal need.",
        ],
        "business" => [
            "slug" => "bl-service",
            "loan_type_id" => 2,
            "label" => "Business Loan",
            "short" => "BL",
            "default_purpose_id" => 4,
            "tagline" => "Working capital, stock purchase, machinery or expansion funding for your business.",
        ],
    ];

    public const STEPS = [
        "verify"      => ["no" => 1, "label" => "Mobile",     "title" => "Start with your mobile number",   "next" => "profile"],
        "profile"     => ["no" => 2, "label" => "Details",    "title" => "A few basic details",             "next" => "employment"],
        "employment"  => ["no" => 3, "label" => "Employment", "title" => "What describes you best?",        "next" => "eligibility"],
        "eligibility" => ["no" => 4, "label" => "Income",     "title" => "Income and credit details",       "next" => "offer"],
        "offer"       => ["no" => 5, "label" => "Offer",      "title" => "Your pre-approved offer",         "next" => "login-type"],
        "login-type"  => ["no" => 6, "label" => "Process",    "title" => "How would you like to proceed?",  "next" => "payment"],
        "payment"     => ["no" => 7, "label" => "Payment",    "title" => "Platform fee payment",            "next" => "banks"],
        "banks"       => ["no" => 7, "label" => "Payment",    "title" => "Select your lending partner",     "next" => "success"],
        "success"     => ["no" => 7, "label" => "Payment",    "title" => "Application submitted",           "next" => null],
        "failed"      => ["no" => 7, "label" => "Payment",    "title" => "Payment could not be completed",  "next" => "payment"],
    ];

    public const TOTAL_STEPS = 7;

    public const EDITABLE_STEPS = ["profile", "employment", "eligibility", "offer", "login-type"];

    public const ROUTE_SUFFIX = [
        "verify" => "Verify",
        "send-otp" => "SendOtp",
        "profile" => "Profile",
        "employment" => "Employment",
        "eligibility" => "Eligibility",
        "offer" => "Offer",
        "login-type" => "LoginType",
        "payment" => "Payment",
        "payment-verify" => "PaymentVerify",
        "banks" => "Banks",
        "success" => "Success",
        "failed" => "Failed",
    ];

    public const TENURES = [12, 24, 36, 48, 60, 72];

    public const SESSION_KEY = "fl_service";

    public function service(Request $request): array
    {
        $type = $request->route("type") ?: "personal";
        if (!array_key_exists($type, self::SERVICES)) {
            abort(404);
        }
        return self::SERVICES[$type] + ["type" => $type];
    }

    public function stepUrl(string $type, string $step): string
    {
        $suffix = self::ROUTE_SUFFIX[$step] ?? "Verify";
        return route("_" . $type . "Service" . $suffix);
    }

    public function sessionUserId(): int
    {
        return (int) (session(self::SESSION_KEY . ".user_id") ?? 0);
    }

    public function sessionUser()
    {
        $user_id = $this->sessionUserId();
        if (0 == $user_id) {
            return null;
        }
        return DB::table("users")->where("id", $user_id)->whereNull("deleted_at")->first();
    }

    public function sessionApplicationId(string $type): int
    {
        return (int) (session(self::SESSION_KEY . ".application." . $type) ?? 0);
    }

    public function putSessionApplicationId(string $type, int $application_id): void
    {
        session()->put(self::SESSION_KEY . ".application." . $type, $application_id);
    }

    public function currentApplication(string $type)
    {
        $user_id = $this->sessionUserId();
        if (0 == $user_id) {
            return null;
        }

        $application_id = $this->sessionApplicationId($type);
        if (0 < $application_id) {
            $loan = DB::table("loan_applications")
                ->where("id", $application_id)
                ->where("user_id", $user_id)
                ->first();
            if ($loan) {
                return $loan;
            }
        }

        $loan = DB::table("loan_applications")
            ->where("user_id", $user_id)
            ->where("loan_type_id", self::SERVICES[$type]["loan_type_id"])
            ->where("status", 1)
            ->orderBy("id", "desc")
            ->first();

        if ($loan) {
            $this->putSessionApplicationId($type, (int) $loan->id);
        }

        return $loan;
    }

    public function feeData(string $login_type = "self"): array
    {
        $all = config("web.fees.login_type", []);
        $cfg = $all[$login_type] ?? ($all["self"] ?? []);

        $gst_rate = (float) config("web.fees.gst_rate", 18);
        $base = (float) ($cfg["base_amount"] ?? 0);
        $gst = round($base * $gst_rate / 100, 2);

        return [
            "login_type" => $login_type,
            "label" => $cfg["label"] ?? "",
            "note" => $cfg["note"] ?? "",
            "payment_gateway" => config("web.fees.payment_gateway", "razorpay"),
            "base_amount" => $base,
            "gst_rate" => $gst_rate,
            "gst_amount" => $gst,
            "total_amount" => round($base + $gst, 2),
        ];
    }

    public function resolveStep(string $type): string
    {
        $user = $this->sessionUser();
        if (!$user) {
            return "verify";
        }

        if (empty($user->email) || empty($user->pincode) || empty($user->city) || empty($user->state_id)) {
            return "profile";
        }

        $loan = $this->currentApplication($type);
        if (!$loan) {
            return "employment";
        }

        if (1 == (int) $loan->payment_status) {
            if ("self" == $loan->login_type && empty($loan->self_login_bank_id)) {
                return "banks";
            }
            return "success";
        }

        return match ((int) $loan->step) {
            1 => "eligibility",
            2 => "offer",
            3, 4, 5, 6 => "login-type",
            7 => "payment",
            default => "employment",
        };
    }

    public function guardStep(Request $request, string $current)
    {
        if (self::DESIGN_PREVIEW && !in_array($current, self::LIVE_STEPS, true)) {
            return null;
        }

        $type = $this->service($request)["type"];
        $allowed = $this->resolveStep($type);

        if ($current === $allowed) {
            return null;
        }

        $order = array_keys(self::STEPS);
        $current_no = array_search($current, $order, true);
        $allowed_no = array_search($allowed, $order, true);

        if (0 < $this->sessionUserId() && "verify" === $current) {
            return redirect()->to($this->stepUrl($type, $allowed));
        }

        if (false !== $current_no && false !== $allowed_no
            && $current_no < $allowed_no
            && in_array($current, self::EDITABLE_STEPS, true)) {
            return null;
        }

        return redirect()->to($this->stepUrl($type, $allowed));
    }

    public function stepView(Request $request, string $step, array $data = [])
    {
        $service = $this->service($request);
        $type = $service["type"];

        $request->merge([
            "header_class" => 1,
        ]);

        $view = $data["view"] ?? ("step" . str_replace(" ", "", ucwords(str_replace("-", " ", $step))) . "Index");
        unset($data["view"]);

        $urls = [];
        foreach (array_keys(self::ROUTE_SUFFIX) as $slug) {
            $urls[$slug] = $this->stepUrl($type, $slug);
        }

        $partners = DB::table("self_login_banks")
            ->select(["id", "label", "logo"])
            ->where("status", 1)
            ->orderBy("label", "asc")
            ->limit(8)
            ->get();

        return view("frontend.services." . $view, array_merge([
            "service" => $service,
            "step" => $step,
            "step_meta" => self::STEPS[$step],
            "total_steps" => self::TOTAL_STEPS,
            "steps" => self::STEPS,
            "urls" => $urls,
            "partners" => $partners,
            "session_user" => $this->sessionUser(),
            "loan" => "verify" === $step ? null : $this->currentApplication($type),
            "preview" => self::DESIGN_PREVIEW,
        ], $data));
    }

    public function stepResponse(string $type, string $next_step, string $message = "")
    {
        return response()->json([
            "message" => $message,
            "step" => $this->stepUrl($type, $next_step),
        ], 200);
    }

    public function stepError(array $messages)
    {
        return response()->json([
            "errors" => ["message" => $messages],
        ], 422);
    }

    private function previewPost(Request $request, string $step)
    {
        if (!self::DESIGN_PREVIEW || in_array($step, self::LIVE_STEPS, true) || "POST" !== $request->method()) {
            return null;
        }
        $type = $this->service($request)["type"];
        $next = self::STEPS[$step]["next"] ?? "verify";
        return $this->stepResponse($type, $next, "Preview: moving to the next step");
    }

    public function landingIndex(Request $request)
    {
        $service = $this->service($request);

        $request->merge([
            "header_class" => 1,
        ]);

        $loan_type = DB::table("loan_types")
            ->where("id", $service["loan_type_id"])
            ->first();

        $partners = DB::table("self_login_banks")
            ->select(["id", "label", "logo"])
            ->where("status", 1)
            ->orderBy("label", "asc")
            ->limit(12)
            ->get();

        return view("frontend.services.landingIndex", [
            "service" => $service,
            "loan_type" => $loan_type,
            "interest_rate" => $loan_type->rate ?? "10.55",
            "partners" => $partners,
            "fee_self" => $this->feeData("self"),
            "fee_consultant" => $this->feeData("consultant"),
        ]);
    }

    public function startIndex(Request $request)
    {
        $type = $this->service($request)["type"];
        return redirect()->to($this->stepUrl($type, $this->resolveStep($type)));
    }

    public function stepVerifyIndex(Request $request, OtpService $otp_service)
    {
        if ("POST" === $request->method()) {
            return $this->verifyOtpPost($request, $otp_service);
        }

        if ($r = $this->guardStep($request, "verify")) {
            return $r;
        }

        $type = $this->service($request)["type"];

        if ($request->boolean("change")) {
            session()->forget(self::SESSION_KEY . ".otp");
            return redirect()->to($this->stepUrl($type, "verify"));
        }

        $otp = session(self::SESSION_KEY . ".otp", []);

        $cooldown = 0;
        if (!empty($otp["sent_at"])) {
            $elapsed = (int) \Carbon\Carbon::parse($otp["sent_at"])->diffInSeconds(now());
            $cooldown = max(0, (int) config("web.sms.otp.cooldown_seconds", 60) - $elapsed);
        }

        return $this->stepView($request, "verify", [
            "otp_sent" => !empty($otp["phone"]),
            "name" => $otp["name"] ?? "",
            "phone" => $otp["phone"] ?? "",
            "cooldown" => $cooldown,
        ]);
    }

    public function sendOtpIndex(Request $request, OtpService $otp_service)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required", "string", "min:2", "max:100", "regex:/^[A-Za-z][A-Za-z .'-]*$/"],
            "phone" => ["required", "digits:10", "regex:/^[6-9][0-9]{9}$/"],
        ], [
            "name.required" => "Please enter your full name.",
            "name.regex" => "Name can only contain letters, spaces, dots and hyphens.",
            "phone.required" => "Please enter your mobile number.",
            "phone.digits" => "Mobile number must be exactly 10 digits.",
            "phone.regex" => "Please enter a valid Indian mobile number.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        $name = trim((string) $request->input("name"));
        $phone = (string) $request->input("phone");

        $existing = DB::table("users")->where("phone", $phone)->first();
        if ($existing && 2 == (int) $existing->status) {
            return $this->stepError(["This account is blocked. Please contact support."]);
        }

        $sent = $otp_service->issue($phone);
        if (true !== $sent["status"]) {
            return $this->stepError([$sent["message"]]);
        }

        session()->put(self::SESSION_KEY . ".otp", [
            "name" => $name,
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
            "readonly" => "#name, #phone",
            "focus" => "#otp",
        ], 200);
    }

    private function verifyOtpPost(Request $request, OtpService $otp_service)
    {
        $type = $this->service($request)["type"];

        $pending = session(self::SESSION_KEY . ".otp", []);
        $phone = (string) ($pending["phone"] ?? "");
        if ("" === $phone) {
            return $this->stepError(["Please request an OTP first."]);
        }

        $validator = Validator::make($request->all(), [
            "otp" => ["required", "digits:6"],
        ], [
            "otp.required" => "Please enter the 6 digit OTP.",
            "otp.digits" => "OTP must be exactly 6 digits.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        $check = $otp_service->verify($phone, (string) $request->input("otp"));
        if (true !== $check["status"]) {
            $max_attempts = (int) config("web.sms.otp.max_attempts", 5);
            $attempts = (int) ($pending["attempts"] ?? 0) + 1;

            if ($attempts >= $max_attempts) {
                DB::table("otp_logs")
                    ->where("phone", $phone)
                    ->where("is_used", 0)
                    ->update(["is_used" => 1, "updated_at" => now()]);
                session()->forget(self::SESSION_KEY . ".otp");

                return response()->json([
                    "errors" => ["message" => ["Too many wrong attempts. Please request a new OTP."]],
                    "step" => $this->stepUrl($type, "verify"),
                ], 422);
            }

            session()->put(self::SESSION_KEY . ".otp.attempts", $attempts);
            $left = $max_attempts - $attempts;

            return $this->stepError([$check["message"] . " " . $left . " attempt" . (1 == $left ? "" : "s") . " left."]);
        }

        $name = trim((string) ($pending["name"] ?? ""));
        $user = DB::table("users")->where("phone", $phone)->first();

        if ($user && 2 == (int) $user->status) {
            return $this->stepError(["This account is blocked. Please contact support."]);
        }

        if (!$user) {
            $user_id = DB::table("users")->insertGetId([
                "uuid" => (string) Str::uuid(),
                "role" => 2,
                "name" => $name,
                "phone" => $phone,
                "status" => 1,
                "mobile_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        } else {
            $update = [
                "mobile_verified_at" => now(),
                "deleted_at" => null,
                "updated_at" => now(),
            ];
            if ("" !== $name) {
                $update["name"] = $name;
            }
            if (0 == (int) $user->status) {
                $update["status"] = 1;
            }
            DB::table("users")->where("id", $user->id)->update($update);
            $user_id = (int) $user->id;
        }

        $request->session()->regenerate();
        session()->forget(self::SESSION_KEY . ".otp");
        session()->put(self::SESSION_KEY . ".user_id", $user_id);

        return $this->stepResponse($type, $this->resolveStep($type), $check["message"]);
    }

    public function stepProfileIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            return $this->profilePost($request);
        }
        if ($r = $this->guardStep($request, "profile")) {
            return $r;
        }

        $states = DB::table("states")
            ->select(["id", "name"])
            ->where("status", 0)
            ->orderBy("name")
            ->get();

        return $this->stepView($request, "profile", [
            "states" => $states,
        ]);
    }

    private function profilePost(Request $request)
    {
        $type = $this->service($request)["type"];

        $user = $this->sessionUser();
        if (!$user) {
            return response()->json([
                "errors" => ["message" => ["Your session has expired. Please verify your mobile number again."]],
                "step" => $this->stepUrl($type, "verify"),
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            "email" => ["required", "string", "email:rfc", "max:255", "unique:users,email," . $user->id],
            "pincode" => ["required", "digits:6", "regex:/^[1-9][0-9]{5}$/"],
            "city" => ["required", "string", "min:2", "max:100", "regex:/^[A-Za-z][A-Za-z .'-]*$/"],
            "state_id" => ["required", "integer", "exists:states,id,status,0"],
        ], [
            "email.required" => "Please enter your email address.",
            "email.email" => "Please enter a valid email address.",
            "email.unique" => "This email is already registered with another mobile number.",
            "pincode.required" => "Please enter your PIN code.",
            "pincode.digits" => "PIN code must be exactly 6 digits.",
            "pincode.regex" => "Please enter a valid Indian PIN code.",
            "city.required" => "Please enter your city.",
            "city.regex" => "City can only contain letters, spaces, dots and hyphens.",
            "state_id.required" => "Please select your state.",
            "state_id.exists" => "Please select a valid state.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        $email = strtolower(trim((string) $request->input("email")));
        $city = trim((string) $request->input("city"));
        $state_id = (int) $request->input("state_id");

        $city_id = DB::table("districts")
            ->where("state_id", $state_id)
            ->where("status", 0)
            ->whereRaw("LOWER(name) = ?", [strtolower($city)])
            ->value("id");
        if (!$city_id) {
            $city_id = DB::table("districts")
                ->where("state_id", $state_id)
                ->where("status", 0)
                ->where("name", "like", $city . "%")
                ->orderBy("name")
                ->value("id");
        }

        DB::table("users")->where("id", $user->id)->update([
            "email" => $email,
            "pincode" => (string) $request->input("pincode"),
            "city" => $city,
            "state_id" => $state_id,
            "city_id" => $city_id ?: null,
            "updated_at" => now(),
        ]);

        return $this->stepResponse($type, $this->resolveStep($type), "Details saved");
    }

    public function stepEmploymentIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "employment")) {
            return $r;
        }
        if ($r = $this->guardStep($request, "employment")) {
            return $r;
        }

        return $this->stepView($request, "employment");
    }

    public function stepEligibilityIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "eligibility")) {
            return $r;
        }
        if ($r = $this->guardStep($request, "eligibility")) {
            return $r;
        }

        $cibil_scores = DB::table("cibil_scores")
            ->select(["id", "label"])
            ->where("status", 1)
            ->orderBy("id")
            ->get();

        return $this->stepView($request, "eligibility", [
            "cibil_scores" => $cibil_scores,
        ]);
    }

    public function stepOfferIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "offer")) {
            return $r;
        }
        if ($r = $this->guardStep($request, "offer")) {
            return $r;
        }

        $service = $this->service($request);
        $loan = $this->currentApplication($service["type"]);

        $loan_type = DB::table("loan_types")->where("id", $service["loan_type_id"])->first();
        $rate = (float) ($loan->interest_rate ?? $loan_type->rate ?? 12.5);

        $income = (float) ($loan->monthly_income ?? 50000);
        $existing_emi = (float) ($loan->existing_emi ?? 0);
        $requested = (float) ($loan->eligible_amount ?? 500000);

        $eligible = $this->checkUserLoanAmountEligiblity($income, $existing_emi, $rate, $requested);
        $offer_amount = min($eligible, max($requested, 50000));

        $tenures = [];
        foreach (self::TENURES as $months) {
            $tenures[] = [
                "months" => $months,
                "emi" => $this->emiCalculation($rate, $months / 12, $offer_amount),
            ];
        }

        return $this->stepView($request, "offer", [
            "rate" => $rate,
            "eligible_amount" => $eligible,
            "offer_amount" => $offer_amount,
            "requested_amount" => $requested,
            "tenures" => $tenures,
            "selected_tenure" => (int) ($loan->tenure_months ?? 36),
        ]);
    }

    public function stepLoginTypeIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "login-type")) {
            return $r;
        }
        if ($r = $this->guardStep($request, "login-type")) {
            return $r;
        }

        $loan = $this->currentApplication($this->service($request)["type"]);

        return $this->stepView($request, "login-type", [
            "fee_self" => $this->feeData("self"),
            "fee_consultant" => $this->feeData("consultant"),
            "selected" => $loan->login_type ?? "self",
        ]);
    }

    public function stepPaymentIndex(Request $request)
    {
        if ($r = $this->guardStep($request, "payment")) {
            return $r;
        }

        $loan = $this->currentApplication($this->service($request)["type"]);
        $login_type = $loan->login_type ?? "self";

        return $this->stepView($request, "payment", [
            "fee" => $this->feeData($login_type),
            "razorpay_key" => env("RAZORPAY_KEY", ""),
        ]);
    }

    public function paymentVerifyIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "payment")) {
            return $r;
        }

        return $this->stepError(["Payment is not available yet."]);
    }

    public function stepBanksIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "banks")) {
            return $r;
        }
        if ($r = $this->guardStep($request, "banks")) {
            return $r;
        }

        $service = $this->service($request);
        $link_col = "business" === $service["type"] ? "bl_link" : "pl_link";

        $banks = DB::table("self_login_banks")
            ->select(["id", "label", "logo", $link_col . " as link"])
            ->where("status", 1)
            ->orderBy("label", "asc")
            ->get();

        return $this->stepView($request, "banks", [
            "banks" => $banks,
        ]);
    }

    public function successIndex(Request $request)
    {
        $service = $this->service($request);
        $loan = $this->currentApplication($service["type"]);

        $bank = null;
        if (!empty($loan->self_login_bank_id)) {
            $bank = DB::table("self_login_banks")->where("id", $loan->self_login_bank_id)->first();
        }
        if (!$bank && self::DESIGN_PREVIEW) {
            $bank = DB::table("self_login_banks")->where("status", 1)->orderBy("label")->first();
        }

        $link = "";
        if ($bank) {
            $link = "business" === $service["type"] ? $bank->bl_link : $bank->pl_link;
        }

        return $this->stepView($request, "success", [
            "view" => "successIndex",
            "login_type" => $loan->login_type ?? "self",
            "application_no" => $loan->application_no ?? "LN-" . date("Ymd") . "-PREVIEW",
            "bank" => $bank,
            "bank_link" => trim($link),
        ]);
    }

    public function failedIndex(Request $request)
    {
        return $this->stepView($request, "failed", [
            "view" => "failedIndex",
        ]);
    }
}
