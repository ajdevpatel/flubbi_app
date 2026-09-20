<?php

namespace App\Http\Controllers\Fend;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoanServiceController extends Controller
{
    public const LOAN_AMOUNT_MIN = 10000;

    public const LOAN_AMOUNT_MAX = 3000000;

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
        "start" => "Start",
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
                ->where("loan_type_id", self::SERVICES[$type]["loan_type_id"])
                ->first();
            if ($loan) {
                return $loan;
            }
            session()->forget(self::SESSION_KEY . ".application." . $type);
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
            && in_array($current, self::EDITABLE_STEPS, true)
            && !$this->isLocked($this->currentApplication($type))) {
            return null;
        }

        return redirect()->to($this->stepUrl($type, $allowed));
    }

    public function isLocked($loan): bool
    {
        return $loan && (1 == (int) $loan->payment_status || 1 != (int) $loan->status);
    }

    public function newApplicationNo(): string
    {
        do {
            $no = "LN-" . now()->format("Ymd") . "-" . strtoupper(Str::random(6));
        } while (DB::table("loan_applications")->where("application_no", $no)->exists());

        return $no;
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

        if ($this->isFinished($this->currentApplication($type))) {
            session()->forget(self::SESSION_KEY . ".application." . $type);
        }

        return redirect()->to($this->stepUrl($type, $this->resolveStep($type)));
    }

    public function isFinished($loan): bool
    {
        if (!$this->isLocked($loan)) {
            return false;
        }
        $awaiting_bank = "self" === $loan->login_type && 1 == (int) $loan->payment_status && empty($loan->self_login_bank_id);
        return !$awaiting_bank;
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
        if ("POST" === $request->method()) {
            return $this->employmentPost($request);
        }
        if ($r = $this->guardStep($request, "employment")) {
            return $r;
        }

        return $this->stepView($request, "employment");
    }

    private function employmentPost(Request $request)
    {
        $service = $this->service($request);
        $type = $service["type"];

        $user = $this->sessionUser();
        if (!$user) {
            return response()->json([
                "errors" => ["message" => ["Your session has expired. Please verify your mobile number again."]],
                "step" => $this->stepUrl($type, "verify"),
            ], 422);
        }

        if ("profile" === $this->resolveStep($type)) {
            return response()->json([
                "errors" => ["message" => ["Please complete your basic details first."]],
                "step" => $this->stepUrl($type, "profile"),
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            "employment_type" => ["required", "in:salaried,self_employed"],
        ], [
            "employment_type.required" => "Please tell us whether you are salaried or self employed.",
            "employment_type.in" => "Please choose a valid employment type.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        $employment_type = (string) $request->input("employment_type");
        $loan = $this->currentApplication($type);

        if ($loan && !$this->isLocked($loan)) {
            DB::table("loan_applications")->where("id", $loan->id)->update([
                "employment_type" => $employment_type,
                "updated_at" => now(),
            ]);
            $loan_id = (int) $loan->id;
        } else {
            $loan_id = DB::table("loan_applications")->insertGetId([
                "user_id" => $user->id,
                "loan_type_id" => $service["loan_type_id"],
                "loan_purpose_id" => $service["default_purpose_id"],
                "employment_type" => $employment_type,
                "application_no" => $this->newApplicationNo(),
                "status" => 1,
                "step" => 1,
                "applied_at" => now(),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }

        $this->putSessionApplicationId($type, $loan_id);

        return $this->stepResponse($type, "eligibility", "Saved");
    }

    public function stepEligibilityIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            return $this->eligibilityPost($request);
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
            "amount_min" => self::LOAN_AMOUNT_MIN,
            "amount_max" => self::LOAN_AMOUNT_MAX,
        ]);
    }

    private function pendingApplicationOrFail(Request $request, string $type)
    {
        if (!$this->sessionUser()) {
            return [null, response()->json([
                "errors" => ["message" => ["Your session has expired. Please verify your mobile number again."]],
                "step" => $this->stepUrl($type, "verify"),
            ], 422)];
        }

        $loan = $this->currentApplication($type);
        if (!$loan || $this->isLocked($loan)) {
            $allowed = $this->resolveStep($type);
            return [null, response()->json([
                "errors" => ["message" => [$loan ? "This application is already submitted." : "Please start your application first."]],
                "step" => $this->stepUrl($type, $allowed),
            ], 422)];
        }

        return [$loan, null];
    }

    private function eligibilityPost(Request $request)
    {
        $service = $this->service($request);
        $type = $service["type"];

        [$loan, $fail] = $this->pendingApplicationOrFail($request, $type);
        if ($fail) {
            return $fail;
        }

        foreach (["monthly_income", "existing_emi", "loan_amount"] as $field) {
            $request->merge([$field => preg_replace("/[,\s]/", "", (string) $request->input($field, ""))]);
        }
        if (!$request->filled("loan_purpose_id")) {
            $request->merge(["loan_purpose_id" => $service["default_purpose_id"]]);
        }

        $validator = Validator::make($request->all(), [
            "monthly_income" => ["required", "integer", "min:5000", "max:10000000"],
            "cibil_score" => ["required", "integer", "exists:cibil_scores,id,status,1"],
            "existing_emi" => ["required", "integer", "min:0", "max:10000000", "lt:monthly_income"],
            "loan_amount" => ["required", "integer", "min:" . self::LOAN_AMOUNT_MIN, "max:" . self::LOAN_AMOUNT_MAX],
            "loan_purpose_id" => ["required", "integer", "exists:loan_purposes,id,status,1"],
        ], [
            "monthly_income.required" => "Please enter your monthly income.",
            "monthly_income.integer" => "Monthly income must be a whole number.",
            "monthly_income.min" => "Monthly income must be at least \u{20B9}5,000.",
            "monthly_income.max" => "Monthly income looks too high. Please check the amount.",
            "cibil_score.required" => "Please select your CIBIL score range.",
            "cibil_score.exists" => "Please select a valid CIBIL score range.",
            "existing_emi.required" => "Please enter your existing EMI (0 if none).",
            "existing_emi.integer" => "Existing EMI must be a whole number.",
            "existing_emi.min" => "Existing EMI cannot be negative.",
            "existing_emi.max" => "Existing EMI looks too high. Please check the amount.",
            "existing_emi.lt" => "Existing EMI must be less than your monthly income.",
            "loan_amount.required" => "Please choose the loan amount you need.",
            "loan_amount.integer" => "Loan amount must be a whole number.",
            "loan_amount.min" => "Loan amount must be at least \u{20B9}" . number_format(self::LOAN_AMOUNT_MIN) . ".",
            "loan_amount.max" => "Loan amount cannot exceed \u{20B9}" . number_format(self::LOAN_AMOUNT_MAX) . ".",
            "loan_purpose_id.exists" => "Please select a valid loan purpose.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        DB::table("loan_applications")->where("id", $loan->id)->update([
            "loan_purpose_id" => (int) $request->input("loan_purpose_id"),
            "monthly_income" => (int) $request->input("monthly_income"),
            "existing_emi" => (int) $request->input("existing_emi"),
            "cibil_score" => (int) $request->input("cibil_score"),
            "eligible_amount" => (int) $request->input("loan_amount"),
            "step" => 1 == (int) $loan->step ? 2 : (int) $loan->step,
            "updated_at" => now(),
        ]);

        return $this->stepResponse($type, "offer", "Saved");
    }

    public function stepOfferIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            return $this->offerPost($request);
        }
        if ($r = $this->guardStep($request, "offer")) {
            return $r;
        }

        $service = $this->service($request);
        $loan = $this->currentApplication($service["type"]);

        return $this->stepView($request, "offer", $this->offerData($service, $loan) + [
            "selected_tenure" => (int) ($loan->tenure_months ?: 36),
        ]);
    }

    public function offerData(array $service, $loan): array
    {
        $loan_type = DB::table("loan_types")->where("id", $service["loan_type_id"])->first();
        $rate = (float) ($loan_type->rate ?? 12.5);

        $income = (float) ($loan->monthly_income ?? 0);
        $existing_emi = (float) ($loan->existing_emi ?? 0);
        $requested = (float) ($loan->eligible_amount ?? self::LOAN_AMOUNT_MIN);

        $eligible = (int) $this->checkUserLoanAmountEligiblity($income, $existing_emi, $rate, $requested);
        $offer_amount = (int) min($eligible, max($requested, self::LOAN_AMOUNT_MIN));

        $tenures = [];
        foreach (self::TENURES as $months) {
            $tenures[] = [
                "months" => $months,
                "emi" => $this->amountFormatIndia($this->emiAmount($rate, $months, $offer_amount)),
            ];
        }

        return [
            "rate" => $rate,
            "eligible_amount" => $eligible,
            "offer_amount" => $offer_amount,
            "requested_amount" => $requested,
            "tenures" => $tenures,
        ];
    }

    public function emiAmount(float $rate, int $months, float $principal): int
    {
        $r = $rate / 1200;
        if ($r <= 0) {
            return (int) round($principal / $months);
        }
        return (int) round($principal * $r * pow(1 + $r, $months) / (pow(1 + $r, $months) - 1));
    }

    private function offerPost(Request $request)
    {
        $service = $this->service($request);
        $type = $service["type"];

        [$loan, $fail] = $this->pendingApplicationOrFail($request, $type);
        if ($fail) {
            return $fail;
        }

        if ((int) $loan->step < 2 || null === $loan->monthly_income) {
            return response()->json([
                "errors" => ["message" => ["Please fill in your income details first."]],
                "step" => $this->stepUrl($type, "eligibility"),
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            "loan_emi" => ["required", "integer", "in:" . implode(",", self::TENURES)],
        ], [
            "loan_emi.required" => "Please choose a tenure.",
            "loan_emi.integer" => "Please choose a valid tenure.",
            "loan_emi.in" => "Please choose a valid tenure.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        $offer = $this->offerData($service, $loan);
        $months = (int) $request->input("loan_emi");

        DB::table("loan_applications")->where("id", $loan->id)->update([
            "eligible_amount" => $offer["offer_amount"],
            "tenure_months" => $months,
            "interest_rate" => $offer["rate"],
            "emi_amount" => $this->emiAmount($offer["rate"], $months, $offer["offer_amount"]),
            "eligibility_status" => "eligible",
            "step" => 2 == (int) $loan->step ? 3 : (int) $loan->step,
            "updated_at" => now(),
        ]);

        return $this->stepResponse($type, "login-type", "Offer accepted");
    }

    public function stepLoginTypeIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            return $this->loginTypePost($request);
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

    private function loginTypePost(Request $request)
    {
        $type = $this->service($request)["type"];

        [$loan, $fail] = $this->pendingApplicationOrFail($request, $type);
        if ($fail) {
            return $fail;
        }

        if ((int) $loan->step < 3 || empty($loan->tenure_months)) {
            return response()->json([
                "errors" => ["message" => ["Please accept your offer first."]],
                "step" => $this->stepUrl($type, "offer"),
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            "login_type" => ["required", "in:self,consultant"],
        ], [
            "login_type.required" => "Please choose how you would like to proceed.",
            "login_type.in" => "Please choose a valid option.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        DB::table("loan_applications")->where("id", $loan->id)->update([
            "login_type" => (string) $request->input("login_type"),
            "step" => max(7, (int) $loan->step),
            "updated_at" => now(),
        ]);

        return $this->stepResponse($type, "payment", "Saved");
    }

    public function stepPaymentIndex(Request $request, RazorpayService $razorpay)
    {
        if ("POST" === $request->method()) {
            return $this->paymentPost($request, $razorpay);
        }
        if ($r = $this->guardStep($request, "payment")) {
            return $r;
        }

        $loan = $this->currentApplication($this->service($request)["type"]);

        return $this->stepView($request, "payment", [
            "fee" => $this->feeData($loan->login_type ?? "self"),
            "gateway_ready" => $razorpay->isConfigured(),
        ]);
    }

    private function paymentPost(Request $request, RazorpayService $razorpay)
    {
        $service = $this->service($request);
        $type = $service["type"];

        [$loan, $fail] = $this->pendingApplicationOrFail($request, $type);
        if ($fail) {
            return $fail;
        }

        if ((int) $loan->step < 7 || empty($loan->login_type)) {
            return response()->json([
                "errors" => ["message" => ["Please choose how you would like to proceed first."]],
                "step" => $this->stepUrl($type, "login-type"),
            ], 422);
        }

        if (!$razorpay->isConfigured()) {
            return $this->stepError(["Online payment is not available right now. Please try again later."]);
        }

        $user = $this->sessionUser();
        $fee = $this->feeData($loan->login_type);

        $order = $razorpay->createOrder($fee["total_amount"], $loan->application_no, [
            "application_no" => $loan->application_no,
            "login_type" => $loan->login_type,
            "user_id" => (string) $user->id,
        ]);

        if (true !== $order["status"]) {
            return $this->stepError([$order["message"]]);
        }

        $tz_data = [
            "base_amount" => $fee["base_amount"],
            "gst_percentage" => $fee["gst_rate"],
            "gst_amount" => $fee["gst_amount"],
            "total_amount" => $fee["total_amount"],
            "payment_gateway" => "razorpay",
            "gateway_payment_id" => null,
            "gateway_transaction_id" => $order["id"],
            "gateway_response" => json_encode(["order" => $order]),
            "status" => "pending",
            "updated_at" => now(),
        ];

        $existing = DB::table("payment_transactions")->where("loan_application_id", $loan->id)->first();
        if ($existing) {
            DB::table("payment_transactions")->where("id", $existing->id)->update($tz_data);
        } else {
            DB::table("payment_transactions")->insert($tz_data + [
                "user_id" => $user->id,
                "loan_application_id" => $loan->id,
                "created_at" => now(),
            ]);
        }

        return response()->json([
            "order" => [
                "key" => $razorpay->key(),
                "order_id" => $order["id"],
                "amount" => $order["amount"],
                "currency" => $order["currency"],
                "name" => config("web.store_data.app_name", "Flubbi"),
                "description" => $fee["label"] . " fee - " . $loan->application_no,
                "prefill" => [
                    "name" => (string) $user->name,
                    "email" => (string) $user->email,
                    "contact" => (string) $user->phone,
                ],
                "notes" => ["application_no" => $loan->application_no],
            ],
            "verify_url" => $this->stepUrl($type, "payment-verify"),
            "failed_url" => $this->stepUrl($type, "failed"),
        ], 200);
    }

    public function paymentVerifyIndex(Request $request, RazorpayService $razorpay)
    {
        $type = $this->service($request)["type"];

        if (!$this->sessionUser()) {
            return response()->json([
                "errors" => ["message" => ["Your session has expired. Please verify your mobile number again."]],
                "step" => $this->stepUrl($type, "verify"),
            ], 422);
        }

        $loan = $this->currentApplication($type);
        if (!$loan) {
            return response()->json([
                "errors" => ["message" => ["Please start your application first."]],
                "step" => $this->stepUrl($type, $this->resolveStep($type)),
            ], 422);
        }

        $after_payment = "self" === $loan->login_type ? "banks" : "success";

        $tz = DB::table("payment_transactions")->where("loan_application_id", $loan->id)->first();
        if (!$tz) {
            return response()->json([
                "errors" => ["message" => ["Please start the payment first."]],
                "step" => $this->stepUrl($type, "payment"),
            ], 422);
        }

        if ("success" === $tz->status && 1 == (int) $loan->payment_status) {
            return $this->stepResponse($type, $after_payment, "Payment already received");
        }

        if ("failed" === $request->input("status")) {
            DB::table("payment_transactions")->where("id", $tz->id)->update([
                "status" => "failed",
                "gateway_response" => json_encode(["failed" => $request->except(["_token", "status"])]),
                "updated_at" => now(),
            ]);
            DB::table("loan_applications")->where("id", $loan->id)->update([
                "payment_status" => 2,
                "updated_at" => now(),
            ]);

            return response()->json([
                "message" => "Payment was not completed",
                "step" => $this->stepUrl($type, "failed"),
            ], 200);
        }

        $validator = Validator::make($request->all(), [
            "razorpay_order_id" => ["required", "string"],
            "razorpay_payment_id" => ["required", "string"],
            "razorpay_signature" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return $this->stepError(["Payment details are incomplete. Please try again."]);
        }

        $order_id = (string) $request->input("razorpay_order_id");
        $payment_id = (string) $request->input("razorpay_payment_id");
        $signature = (string) $request->input("razorpay_signature");

        if ($order_id !== (string) $tz->gateway_transaction_id
            || !$razorpay->verifySignature($order_id, $payment_id, $signature)) {
            DB::table("payment_transactions")->where("id", $tz->id)->update([
                "status" => "failed",
                "gateway_payment_id" => $payment_id,
                "gateway_response" => json_encode(["rejected" => $request->except(["_token"])]),
                "updated_at" => now(),
            ]);
            DB::table("loan_applications")->where("id", $loan->id)->update([
                "payment_status" => 2,
                "updated_at" => now(),
            ]);

            return response()->json([
                "errors" => ["message" => ["Payment could not be verified. If money was deducted it will be refunded automatically."]],
                "step" => $this->stepUrl($type, "failed"),
            ], 422);
        }

        DB::transaction(function () use ($tz, $loan, $payment_id, $request) {
            DB::table("payment_transactions")->where("id", $tz->id)->update([
                "status" => "success",
                "gateway_payment_id" => $payment_id,
                "gateway_response" => json_encode(["payment" => $request->except(["_token"])]),
                "updated_at" => now(),
            ]);
            DB::table("loan_applications")->where("id", $loan->id)->update([
                "status" => 2,
                "payment_status" => 1,
                "step" => 8,
                "updated_at" => now(),
            ]);
        });

        return $this->stepResponse($type, $after_payment, "Payment successful");
    }

    public function stepBanksIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            return $this->banksPost($request);
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

    private function banksPost(Request $request)
    {
        $service = $this->service($request);
        $type = $service["type"];

        if (!$this->sessionUser()) {
            return response()->json([
                "errors" => ["message" => ["Your session has expired. Please verify your mobile number again."]],
                "step" => $this->stepUrl($type, "verify"),
            ], 422);
        }

        $loan = $this->currentApplication($type);
        if (!$loan || 1 != (int) $loan->payment_status || "self" !== $loan->login_type) {
            return response()->json([
                "errors" => ["message" => ["Please complete the platform fee payment first."]],
                "step" => $this->stepUrl($type, $this->resolveStep($type)),
            ], 422);
        }

        if (!empty($loan->self_login_bank_id)) {
            return response()->json([
                "errors" => ["message" => ["You have already selected a lending partner."]],
                "step" => $this->stepUrl($type, "success"),
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            "bank_id" => ["required", "integer", "exists:self_login_banks,id,status,1"],
        ], [
            "bank_id.required" => "Please select a lending partner.",
            "bank_id.exists" => "Please select a valid lending partner.",
        ]);

        if ($validator->fails()) {
            return $this->stepError([$validator->errors()->first()]);
        }

        $bank = DB::table("self_login_banks")->where("id", (int) $request->input("bank_id"))->first();
        $link = trim((string) ("business" === $type ? $bank->bl_link : $bank->pl_link));

        DB::table("loan_applications")->where("id", $loan->id)->update([
            "self_login_bank_id" => $bank->id,
            "updated_at" => now(),
        ]);

        return response()->json([
            "message" => ucwords($bank->label) . " selected. Opening their application page in a new tab.",
            "step" => route("_userApplicationShow", $loan->application_no),
            "open" => $link,
        ], 200);
    }

    public function successIndex(Request $request)
    {
        $service = $this->service($request);
        $type = $service["type"];
        $loan = $this->currentApplication($type);

        if (!$loan || 1 != (int) $loan->payment_status) {
            return redirect()->to($this->stepUrl($type, $this->resolveStep($type)));
        }

        $bank = null;
        $link = "";
        if (!empty($loan->self_login_bank_id)) {
            $bank = DB::table("self_login_banks")->where("id", $loan->self_login_bank_id)->first();
            if ($bank) {
                $link = trim((string) ("business" === $type ? $bank->bl_link : $bank->pl_link));
            }
        }

        return $this->stepView($request, "success", [
            "view" => "successIndex",
            "login_type" => $loan->login_type,
            "application_no" => $loan->application_no,
            "bank" => $bank,
            "bank_link" => $link,
        ]);
    }

    public function failedIndex(Request $request)
    {
        $type = $this->service($request)["type"];

        if (!$this->currentApplication($type)) {
            return redirect()->to($this->stepUrl($type, $this->resolveStep($type)));
        }

        return $this->stepView($request, "failed", [
            "view" => "failedIndex",
        ]);
    }
}
