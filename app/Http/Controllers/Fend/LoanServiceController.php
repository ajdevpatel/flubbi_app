<?php

namespace App\Http\Controllers\Fend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Website loan flow for pl-service (Personal Loan) and bl-service (Business Loan).
 *
 * Both services run through this single controller and a single set of views -
 * only the resolved $type ("personal" | "business") differs.
 *
 * Every step is its own route + its own blade. A step submit is an AJAX POST
 * that returns {"step": "<next url>"} and the browser then does a full page
 * load, so progress always survives a refresh. Where the user is standing is
 * derived from the DB (users row + loan_applications.step), never from JS -
 * that is what lets somebody come back days later and land on the step they
 * got stuck on.
 */
class LoanServiceController extends Controller
{
    /**
     * DESIGN PREVIEW MODE
     * While true every step renders its real design, the step guard is off and
     * a POST simply answers with the next step URL without validating or saving
     * anything - so the whole flow can be clicked through on phone + desktop.
     * Flip to false once the step logic is wired in.
     */
    public const DESIGN_PREVIEW = true;

    /*
    |--------------------------------------------------------------------------
    | Service + step definitions
    |--------------------------------------------------------------------------
    */

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

    /** Flow order. "no" drives the progress bar, so verify + otp share step 1. */
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

    /** Steps the user may walk back into to change an answer. */
    public const EDITABLE_STEPS = ["profile", "employment", "eligibility", "offer", "login-type"];

    /** step slug => route-name suffix */
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

    /** Tenures offered on the pre-approved screen (months) - same list the app uses. */
    public const TENURES = [12, 24, 36, 48, 60, 72];

    public const SESSION_KEY = "fl_service";

    /*
    |--------------------------------------------------------------------------
    | Service / session helpers
    |--------------------------------------------------------------------------
    */

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

    /** Application ids are stored per service so PL and BL never overwrite each other. */
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

        // Nothing pinned in the session - fall back to an unfinished application
        // of this type, which is how a returning visitor resumes.
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

    /*
    |--------------------------------------------------------------------------
    | Fees - website only. The mobile app keeps using API payment_data().
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Where is the user standing right now?
    |--------------------------------------------------------------------------
    */

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

    /**
     * Keeps the user on a step they are actually allowed to be on.
     * Returns a RedirectResponse when the request must be bounced, else null.
     */
    public function guardStep(Request $request, string $current)
    {
        if (self::DESIGN_PREVIEW) {
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

        // Already identified - never send them back to the OTP screen.
        if (0 < $this->sessionUserId() && "verify" === $current) {
            return redirect()->to($this->stepUrl($type, $allowed));
        }

        // Walking back to change an earlier answer is fine.
        if (false !== $current_no && false !== $allowed_no
            && $current_no < $allowed_no
            && in_array($current, self::EDITABLE_STEPS, true)) {
            return null;
        }

        return redirect()->to($this->stepUrl($type, $allowed));
    }

    /** Everything a step blade needs to draw itself. */
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

    /** Cashvizta style AJAX reply - the browser then does window.location = step. */
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

    /**
     * Design preview: a POST just walks to the next step. Returns null when the
     * request should be handled for real (GET, or preview switched off).
     */
    private function previewPost(Request $request, string $step)
    {
        if (!self::DESIGN_PREVIEW || "POST" !== $request->method()) {
            return null;
        }
        $type = $this->service($request)["type"];
        $next = self::STEPS[$step]["next"] ?? "verify";
        return $this->stepResponse($type, $next, "Preview: moving to the next step");
    }

    /*
    |--------------------------------------------------------------------------
    | Landing + entry
    |--------------------------------------------------------------------------
    */

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

    /** Single entry point - works out where the visitor belongs and sends them there. */
    public function startIndex(Request $request)
    {
        $type = $this->service($request)["type"];
        return redirect()->to($this->stepUrl($type, $this->resolveStep($type)));
    }

    /*
    |--------------------------------------------------------------------------
    | Step 1 - mobile number + OTP
    |--------------------------------------------------------------------------
    */

    /**
     * One screen: name + mobile -> "Send OTP" (side POST, stays on the page and
     * reveals the OTP box) -> "Verify & continue" (step POST -> profile).
     * The OTP box state lives in the session, so a refresh keeps it open.
     */
    public function stepVerifyIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "verify")) {
            return $r;
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

        return $this->stepView($request, "verify", [
            "otp_sent" => !empty($otp["phone"]),
            "name" => $otp["name"] ?? "",
            "phone" => $otp["phone"] ?? "",
            "cooldown" => 60,
        ]);
    }

    /** Side POST from the verify screen - sends the OTP and reveals the OTP box. */
    public function sendOtpIndex(Request $request)
    {
        if (self::DESIGN_PREVIEW) {
            session()->put(self::SESSION_KEY . ".otp", [
                "name" => (string) $request->input("name", ""),
                "phone" => (string) $request->input("phone", ""),
                "sent_at" => now()->toDateTimeString(),
            ]);

            return response()->json([
                "message" => "Preview: OTP sent (nothing is really sent yet)",
                "otp_sent" => true,
                "cooldown" => 60,
                "cooldown_target" => "#fl-resend-btn",
                "show" => "#fl-otp-wrap",
                "hide" => "#fl-send-wrap",
                "readonly" => "#name, #phone",
                "focus" => "#otp",
            ], 200);
        }

        return $this->stepError(["OTP sending is not available yet."]);
    }

    /*
    |--------------------------------------------------------------------------
    | Step 2 - email, pincode, state, city
    |--------------------------------------------------------------------------
    */

    public function stepProfileIndex(Request $request)
    {
        if ($r = $this->previewPost($request, "profile")) {
            return $r;
        }
        if ($r = $this->guardStep($request, "profile")) {
            return $r;
        }

        // City is free text (spec order: Email, PIN, City, State). On save it is
        // matched against districts to fill users.city_id when a name lines up.
        $states = DB::table("states")
            ->select(["id", "name"])
            ->where("status", 0)
            ->orderBy("name")
            ->get();

        return $this->stepView($request, "profile", [
            "states" => $states,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Step 3 - employment type
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Step 4 - income, cibil, existing emi, amount required
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Step 5 - pre-approved offer + tenure
    |--------------------------------------------------------------------------
    */

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

        // Real values once the eligibility step is wired; sample figures until then.
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

    /*
    |--------------------------------------------------------------------------
    | Step 6 - self login vs hire agent
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Step 7 - payment, then banks (self) / success (agent)
    |--------------------------------------------------------------------------
    */

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
