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
            "tagline" => "Funds for a medical emergency, wedding, travel, education or any personal need.",
        ],
        "business" => [
            "slug" => "bl-service",
            "loan_type_id" => 2,
            "label" => "Business Loan",
            "short" => "BL",
            "tagline" => "Working capital, stock purchase, machinery or expansion funding for your business.",
        ],
    ];

    /** Flow order. "no" drives the progress bar, so verify + otp share step 1. */
    public const STEPS = [
        "verify"      => ["no" => 1, "label" => "Mobile",     "title" => "Start with your mobile number"],
        "otp"         => ["no" => 1, "label" => "Mobile",     "title" => "Verify the OTP"],
        "profile"     => ["no" => 2, "label" => "Details",    "title" => "A few basic details"],
        "employment"  => ["no" => 3, "label" => "Employment", "title" => "What describes you best?"],
        "eligibility" => ["no" => 4, "label" => "Income",     "title" => "Income and credit details"],
        "offer"       => ["no" => 5, "label" => "Offer",      "title" => "Your pre-approved offer"],
        "login-type"  => ["no" => 6, "label" => "Process",    "title" => "How would you like to proceed?"],
        "payment"     => ["no" => 7, "label" => "Payment",    "title" => "Platform fee payment"],
        "banks"       => ["no" => 7, "label" => "Payment",    "title" => "Select your lending partner"],
        "success"     => ["no" => 7, "label" => "Payment",    "title" => "Application submitted"],
        "failed"      => ["no" => 7, "label" => "Payment",    "title" => "Payment could not be completed"],
    ];

    public const TOTAL_STEPS = 7;

    /** Steps the user may walk back into to change an answer. */
    public const EDITABLE_STEPS = ["profile", "employment", "eligibility", "offer", "login-type"];

    /** step slug => route-name suffix */
    public const ROUTE_SUFFIX = [
        "verify" => "Verify",
        "otp" => "Otp",
        "profile" => "Profile",
        "employment" => "Employment",
        "eligibility" => "Eligibility",
        "offer" => "Offer",
        "login-type" => "LoginType",
        "payment" => "Payment",
        "banks" => "Banks",
        "success" => "Success",
        "failed" => "Failed",
    ];

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
            return session()->has(self::SESSION_KEY . ".otp.phone") ? "otp" : "verify";
        }

        if (empty($user->email) || empty($user->state_id) || empty($user->city_id) || empty($user->pincode)) {
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
        $type = $this->service($request)["type"];
        $allowed = $this->resolveStep($type);

        if ($current === $allowed) {
            return null;
        }

        $order = array_keys(self::STEPS);
        $current_no = array_search($current, $order, true);
        $allowed_no = array_search($allowed, $order, true);

        // Already identified - never send them back to the OTP screens.
        if (0 < $this->sessionUserId() && in_array($current, ["verify", "otp"], true)) {
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

        $request->merge([
            "header_class" => 1,
        ]);

        $view = $data["view"] ?? "stepPendingIndex";
        unset($data["view"]);

        return view("frontend.services." . $view, array_merge([
            "service" => $service,
            "step" => $step,
            "step_meta" => self::STEPS[$step],
            "total_steps" => self::TOTAL_STEPS,
            "steps" => self::STEPS,
            "session_user" => $this->sessionUser(),
            "loan" => in_array($step, ["verify", "otp"], true) ? null : $this->currentApplication($service["type"]),
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
    | Steps - filled in one phase at a time
    |--------------------------------------------------------------------------
    */

    public function stepVerifyIndex(Request $request)
    {
        return $this->pendingStep($request, "verify");
    }

    public function stepOtpIndex(Request $request)
    {
        return $this->pendingStep($request, "otp");
    }

    public function stepProfileIndex(Request $request)
    {
        return $this->pendingStep($request, "profile");
    }

    public function stepEmploymentIndex(Request $request)
    {
        return $this->pendingStep($request, "employment");
    }

    public function stepEligibilityIndex(Request $request)
    {
        return $this->pendingStep($request, "eligibility");
    }

    public function stepOfferIndex(Request $request)
    {
        return $this->pendingStep($request, "offer");
    }

    public function stepLoginTypeIndex(Request $request)
    {
        return $this->pendingStep($request, "login-type");
    }

    public function stepPaymentIndex(Request $request)
    {
        return $this->pendingStep($request, "payment");
    }

    public function paymentVerifyIndex(Request $request)
    {
        return $this->stepError(["Payment is not available yet."]);
    }

    public function stepBanksIndex(Request $request)
    {
        return $this->pendingStep($request, "banks");
    }

    public function successIndex(Request $request)
    {
        return $this->pendingStep($request, "success");
    }

    public function failedIndex(Request $request)
    {
        return $this->pendingStep($request, "failed");
    }

    /** Scaffold used by steps that have not been implemented yet. */
    private function pendingStep(Request $request, string $step)
    {
        if ("POST" === $request->method()) {
            return $this->stepError(["This step is not available yet."]);
        }

        return $this->stepView($request, $step, [
            "view" => "stepPendingIndex",
        ]);
    }
}
