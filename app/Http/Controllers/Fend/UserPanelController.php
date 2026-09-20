<?php

namespace App\Http\Controllers\Fend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    private function panelView(Request $request, string $view, array $data = [])
    {
        $request->merge(["header_class" => 1]);

        $user = $this->sessionUser();
        $sample = null === $user;
        if ($sample) {
            $user = $this->sampleUser();
        }

        return view("frontend.user." . $view, array_merge([
            "user" => $user,
            "sample" => $sample,
            "unread" => $sample ? 2 : DB::table("notifications")->where("user_id", $user->id)->where("is_read", 0)->count(),
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

    private function applications($user, bool $sample)
    {
        if ($sample) {
            return collect($this->sampleApplications());
        }

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

    public function loginIndex(Request $request)
    {
        $request->merge(["header_class" => 1]);

        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Login is not available yet."]]], 422);
        }

        return view("frontend.user.loginIndex", [
            "otp_sent" => $request->boolean("otp"),
            "phone" => $request->boolean("otp") ? "98XXXXXX10" : "",
        ]);
    }

    public function loginSendOtp(Request $request)
    {
        return response()->json(["errors" => ["message" => ["Login is not available yet."]]], 422);
    }

    public function logoutIndex(Request $request)
    {
        session()->forget(self::SESSION_KEY);
        $request->session()->regenerate();
        return redirect()->route("_homeIndex")->with("success", "You have been logged out.");
    }

    public function dashboardIndex(Request $request)
    {
        $user = $this->sessionUser();
        $sample = null === $user;
        $apps = $this->applications($user ?? $this->sampleUser(), $sample);

        return $this->panelView($request, "dashboardIndex", [
            "applications" => $apps,
            "latest" => $apps->first(),
            "stats" => [
                "total" => $apps->count(),
                "active" => $apps->whereIn("status", [1, 2, 3])->count(),
                "paid" => $apps->where("payment_status", 1)->count(),
                "disbursed" => $apps->where("status", 5)->count(),
            ],
            "notifications" => $sample ? array_slice($this->sampleNotifications(), 0, 3)
                : DB::table("notifications")->where("user_id", $user->id)->orderByDesc("id")->limit(3)->get(),
        ]);
    }

    public function applicationsIndex(Request $request)
    {
        $user = $this->sessionUser();
        $sample = null === $user;

        return $this->panelView($request, "applicationsIndex", [
            "applications" => $this->applications($user ?? $this->sampleUser(), $sample),
        ]);
    }

    public function applicationShow(Request $request, string $application)
    {
        $user = $this->sessionUser();
        $sample = null === $user;
        $apps = $this->applications($user ?? $this->sampleUser(), $sample);
        $loan = $apps->firstWhere("application_no", $application) ?? $apps->first();

        if (!$loan) {
            return redirect()->route("_userApplications");
        }

        $detail = $sample ? (object) $this->sampleDetail($loan) : DB::table("loan_applications as la")
            ->leftJoin("loan_purposes as lp", "lp.id", "=", "la.loan_purpose_id")
            ->leftJoin("cibil_scores as cs", "cs.id", "=", "la.cibil_score")
            ->select(["la.*", "lp.label as purpose", "cs.label as cibil_label"])
            ->where("la.id", $loan->id)
            ->first();

        $bank_link = "";
        if (!empty($loan->self_login_bank_id)) {
            $bank = $sample ? null : DB::table("self_login_banks")->where("id", $loan->self_login_bank_id)->first();
            $bank_link = $bank ? trim((string) (2 == $loan->loan_type_id ? $bank->bl_link : $bank->pl_link)) : "https://example.com/apply";
        }

        $history = $sample ? $this->sampleHistory() : DB::table("application_history as h")
            ->leftJoin("loan_status as ls", "ls.id", "=", "h.status_id")
            ->select(["h.*", "ls.label"])
            ->where("h.application_id", $loan->id)
            ->orderBy("h.id")
            ->get();

        return $this->panelView($request, "applicationShowIndex", [
            "loan" => $loan,
            "detail" => $detail,
            "bank_link" => $bank_link,
            "history" => $history,
            "docs" => $this->documentStatus($loan, $sample),
        ]);
    }

    public function documentsIndex(Request $request, string $application = "")
    {
        $user = $this->sessionUser();
        $sample = null === $user;
        $apps = $this->applications($user ?? $this->sampleUser(), $sample);
        $loan = $apps->firstWhere("application_no", $application) ?? $apps->first();

        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Document upload is not available yet."]]], 422);
        }

        return $this->panelView($request, "documentsIndex", [
            "applications" => $apps,
            "loan" => $loan,
            "docs" => $loan ? $this->documentStatus($loan, $sample) : [],
        ]);
    }

    private function documentStatus($loan, bool $sample): array
    {
        $row = $sample ? null : DB::table("loan_documents")->where("loan_application_id", $loan->id)->first();
        $groups = ["kyc" => self::DOCUMENTS["kyc"]];
        $groups["self_employed" === ($loan->employment_type ?? "salaried") ? "self_employed" : "salaried"] =
            self::DOCUMENTS["self_employed" === ($loan->employment_type ?? "salaried") ? "self_employed" : "salaried"];
        $groups["other"] = self::DOCUMENTS["other"];

        $out = [];
        foreach ($groups as $group => $items) {
            foreach ($items as $key => $label) {
                $file = in_array($key, ["aadhar_front", "aadhar_back", "pan_front", "selfie"], true)
                    ? ($loan->$key ?? null)
                    : ($row->$key ?? null);
                if ($sample) {
                    $file = in_array($key, ["aadhar_front", "pan_front", "selfie"], true) ? "sample.jpg" : null;
                }
                $out[$group][] = [
                    "key" => $key,
                    "label" => $label,
                    "file" => $file,
                    "url" => $file ? ($sample ? asset("assets/images/home_3/auothor.png") : asset("uploads/" . $file)) : "",
                    "status" => $file ? (($row->status ?? "pending") === "rejected" ? "rejected" : "uploaded") : "missing",
                ];
            }
        }
        return $out;
    }

    public function profileIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Profile update is not available yet."]]], 422);
        }

        return $this->panelView($request, "profileIndex", [
            "states" => DB::table("states")->select(["id", "name"])->where("status", 0)->orderBy("name")->get(),
        ]);
    }

    public function supportIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Support tickets are not available yet."]]], 422);
        }

        $user = $this->sessionUser();
        $sample = null === $user;

        return $this->panelView($request, "supportIndex", [
            "reasons" => DB::table("support_reasons")->select(["id", "label"])->where("status", 1)->orderBy("id")->get(),
            "tickets" => $sample ? $this->sampleTickets() : DB::table("support_tickets as t")
                ->leftJoin("support_reasons as r", "r.id", "=", "t.reason_id")
                ->select(["t.*", "r.label as reason"])
                ->where("t.user_id", $user->id)
                ->orderByDesc("t.id")
                ->get(),
        ]);
    }

    public function notificationsIndex(Request $request)
    {
        $user = $this->sessionUser();
        $sample = null === $user;

        return $this->panelView($request, "notificationsIndex", [
            "notifications" => $sample ? $this->sampleNotifications()
                : DB::table("notifications")->where("user_id", $user->id)->orderByDesc("id")->limit(50)->get(),
        ]);
    }

    public function transactionsIndex(Request $request)
    {
        $user = $this->sessionUser();
        $sample = null === $user;

        return $this->panelView($request, "transactionsIndex", [
            "transactions" => $sample ? $this->sampleTransactions() : DB::table("payment_transactions as pt")
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
        if ("POST" === $request->method()) {
            return response()->json(["errors" => ["message" => ["Account deletion is not available yet."]]], 422);
        }

        return $this->panelView($request, "deleteAccountIndex");
    }

    private function sampleUser(): object
    {
        return (object) [
            "id" => 0,
            "name" => "Rahul Mehta",
            "phone" => "98XXXXXX10",
            "email" => "rahul@example.com",
            "gender" => "male",
            "state_id" => 7,
            "state_name" => "Gujarat",
            "city" => "Surat",
            "pincode" => "395004",
            "profile_pic" => null,
            "created_at" => now()->subMonths(2)->toDateTimeString(),
        ];
    }

    private function sampleApplications(): array
    {
        return [
            (object) [
                "id" => 0, "application_no" => "LN-20260918-K7P2QX", "loan_type_id" => 1, "loan_type" => "Personal Loan",
                "employment_type" => "salaried", "monthly_income" => 45000, "existing_emi" => 5000, "cibil_score" => 2,
                "eligible_amount" => 500000, "tenure_months" => 36, "interest_rate" => 10.55, "emi_amount" => 16204,
                "status" => 2, "status_label" => "Under Review", "step" => 8, "login_type" => "self", "payment_status" => 1,
                "tz_status" => "success", "tz_amount" => 352.82, "self_login_bank_id" => 2, "bank_name" => "Werize", "bank_logo" => "044.png",
                "applied_at" => now()->subDays(2)->toDateTimeString(), "aadhar_front" => "x", "aadhar_back" => null, "pan_front" => "x", "selfie" => "x",
            ],
            (object) [
                "id" => 0, "application_no" => "LN-20260905-A1B2C3", "loan_type_id" => 2, "loan_type" => "Business Loan",
                "employment_type" => "self_employed", "monthly_income" => 80000, "existing_emi" => 0, "cibil_score" => 3,
                "eligible_amount" => 875000, "tenure_months" => 48, "interest_rate" => 12.55, "emi_amount" => 23167,
                "status" => 1, "status_label" => "Pending", "step" => 3, "login_type" => null, "payment_status" => 0,
                "tz_status" => null, "tz_amount" => null, "self_login_bank_id" => null, "bank_name" => null, "bank_logo" => null,
                "applied_at" => now()->subDays(15)->toDateTimeString(), "aadhar_front" => null, "aadhar_back" => null, "pan_front" => null, "selfie" => null,
            ],
        ];
    }

    private function sampleDetail($loan): array
    {
        return (array) $loan + ["purpose" => 1 == $loan->loan_type_id ? "Personal Expenses" : "Business Expansion", "cibil_label" => "651 - 750"];
    }

    private function sampleHistory()
    {
        return collect([
            (object) ["label" => "Pending", "remarks" => "Application received", "created_at" => now()->subDays(2)->toDateTimeString()],
            (object) ["label" => "Under Review", "remarks" => "Documents being verified by our team", "created_at" => now()->subDay()->toDateTimeString()],
        ]);
    }

    private function sampleNotifications(): array
    {
        return [
            (object) ["title" => "Application under review", "message" => "Your personal loan application LN-20260918-K7P2QX is being reviewed.", "is_read" => 0, "created_at" => now()->subHours(3)->toDateTimeString()],
            (object) ["title" => "Payment received", "message" => "We received your platform fee of \u{20B9}352.82. Thank you!", "is_read" => 0, "created_at" => now()->subDays(2)->toDateTimeString()],
            (object) ["title" => "Welcome to Flubbi", "message" => "Your account is ready. Check your pre-approved offers any time.", "is_read" => 1, "created_at" => now()->subMonths(2)->toDateTimeString()],
        ];
    }

    private function sampleTickets()
    {
        return collect([
            (object) ["ticket_no" => "ST-20260919-3F8KQ2", "reason" => "Payment issue", "message" => "Paid the fee but bank list did not open.", "status" => "open", "created_at" => now()->subDay()->toDateTimeString()],
            (object) ["ticket_no" => "ST-20260901-9ZD1LM", "reason" => "Documents", "message" => "How do I re-upload my PAN card?", "status" => "closed", "created_at" => now()->subDays(19)->toDateTimeString()],
        ]);
    }

    private function sampleTransactions()
    {
        return collect([
            (object) ["application_no" => "LN-20260918-K7P2QX", "loan_type" => "Personal Loan", "login_type" => "self", "base_amount" => 299, "gst_amount" => 53.82, "total_amount" => 352.82, "payment_gateway" => "razorpay", "gateway_payment_id" => "pay_R8kf3XxPq1", "status" => "success", "created_at" => now()->subDays(2)->toDateTimeString()],
            (object) ["application_no" => "LN-20260820-PQ7M2N", "loan_type" => "Business Loan", "login_type" => "consultant", "base_amount" => 499, "gst_amount" => 89.82, "total_amount" => 588.82, "payment_gateway" => "razorpay", "gateway_payment_id" => null, "status" => "failed", "created_at" => now()->subMonth()->toDateTimeString()],
        ]);
    }
}
