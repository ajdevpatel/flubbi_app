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
            ->whereIn("u.status", [0, 1])
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
            if (!empty($check["burned"])) {
                session()->forget(self::SESSION_KEY . ".login_otp");
                return response()->json([
                    "errors" => ["message" => [$check["message"]]],
                    "step" => route("_userLogin"),
                ], 422);
            }

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

        $docs_allowed = $this->documentsAllowed($loan);

        return $this->panelView($request, "applicationShowIndex", $user, [
            "loan" => $loan,
            "detail" => $detail,
            "bank_link" => $bank_link,
            "history" => $history,
            "docs_allowed" => $docs_allowed,
            "docs" => $docs_allowed ? $this->documentStatus($loan) : [],
        ]);
    }

    public function documentsIndex(Request $request, string $application = "")
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        $apps = $this->applications($user);
        $eligible = $apps->filter(fn ($a) => $this->documentsAllowed($a))->values();
        $loan = $eligible->firstWhere("application_no", $application) ?? $eligible->first();

        if ("POST" === $request->method()) {
            return $this->documentPost($request, $user, $application, $apps);
        }

        return $this->panelView($request, "documentsIndex", $user, [
            "applications" => $eligible,
            "has_applications" => $apps->isNotEmpty(),
            "loan" => $loan,
            "docs" => $loan ? $this->documentStatus($loan) : [],
        ]);
    }

    public function documentsAllowed($loan): bool
    {
        return $loan && "consultant" === $loan->login_type && 1 == (int) $loan->payment_status;
    }

    private function documentPost(Request $request, $user, string $application, $apps)
    {
        $loan = $apps->firstWhere("application_no", $application);
        if (!$loan) {
            return response()->json(["errors" => ["message" => ["Application not found."]]], 422);
        }
        if (!$this->documentsAllowed($loan)) {
            return response()->json(["errors" => ["message" => ["Documents can be uploaded only for Hire Agent applications after the platform fee is paid."]]], 422);
        }

        $types = array_merge(...array_values(array_map("array_keys", self::DOCUMENTS)));
        $doc_type = (string) $request->input("doc_type");

        $rules = [
            "doc_type" => ["required", "in:" . implode(",", $types)],
            "file" => ["required", "file", "mimes:jpg,jpeg,png,pdf", "max:10240"],
        ];
        if (in_array($doc_type, ["aadhar_front", "aadhar_back"], true)) {
            $rules["aadhar_number"] = ["required", "digits:12"];
        }
        if ("pan_front" === $doc_type) {
            $request->merge(["pan_number" => strtoupper(trim((string) $request->input("pan_number")))]);
            $rules["pan_number"] = ["required", "regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/"];
        }

        $validator = Validator::make($request->all(), $rules, [
            "doc_type.required" => "Please choose which document you are uploading.",
            "doc_type.in" => "Unknown document type.",
            "file.required" => "Please choose a file.",
            "file.mimes" => "Only JPG, PNG or PDF files are accepted.",
            "file.max" => "File must be 10 MB or smaller.",
            "aadhar_number.required" => "Please enter your 12 digit Aadhaar number.",
            "aadhar_number.digits" => "Aadhaar number must be exactly 12 digits.",
            "pan_number.required" => "Please enter your PAN number.",
            "pan_number.regex" => "PAN must look like ABCDE1234F.",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => ["message" => [$validator->errors()->first()]]], 422);
        }

        $dir = public_path("uploads");
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $file = $request->file("file");
        $name = $loan->id . "_" . $doc_type . "_" . random_int(100000, 999999) . "." . strtolower($file->getClientOriginalExtension());
        $file->move($dir, $name);

        if (array_key_exists($doc_type, self::DOCUMENTS["kyc"])) {
            $old = $loan->$doc_type ?? null;
            $update = [$doc_type => $name, "updated_at" => now()];
            if ("pan_front" === $doc_type) {
                $update["pan_number"] = (string) $request->input("pan_number");
            } else {
                $update["aadhar_number"] = (string) $request->input("aadhar_number");
            }
            DB::table("loan_applications")->where("id", $loan->id)->update($update);
        } else {
            $row = DB::table("loan_documents")->where("loan_application_id", $loan->id)->first();
            if (!$row) {
                DB::table("loan_documents")->insert([
                    "loan_application_id" => $loan->id,
                    "user_id" => $user->id,
                    $doc_type => $name,
                    "status" => "pending",
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                $old = null;
            } else {
                $old = $row->$doc_type ?? null;
                DB::table("loan_documents")->where("id", $row->id)->update([
                    $doc_type => $name,
                    "status" => "pending",
                    "rejection_reason" => null,
                    "updated_at" => now(),
                ]);
            }
        }

        if ($old && str_starts_with($old, $loan->id . "_" . $doc_type . "_") && is_file($dir . DIRECTORY_SEPARATOR . $old)) {
            @unlink($dir . DIRECTORY_SEPARATOR . $old);
        }

        $labels = array_merge(...array_values(self::DOCUMENTS));

        return response()->json([
            "message" => ($labels[$doc_type] ?? "Document") . " uploaded",
            "step" => route("_userDocuments", $loan->application_no),
        ], 200);
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
                $is_kyc = array_key_exists($key, self::DOCUMENTS["kyc"]);
                $file = $is_kyc ? ($loan->$key ?? null) : ($row->$key ?? null);
                $out[$group][] = [
                    "key" => $key,
                    "label" => $label,
                    "file" => $file,
                    "url" => $file ? asset("uploads/" . $file) : "",
                    "status" => $file ? (!$is_kyc && "rejected" === ($row->status ?? "") ? "rejected" : "uploaded") : "missing",
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
            return $this->profilePost($request, $user);
        }

        return $this->panelView($request, "profileIndex", $user, [
            "states" => DB::table("states")->select(["id", "name"])->where("status", 0)->orderBy("name")->get(),
        ]);
    }

    private function profilePost(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required", "string", "min:2", "max:100", "regex:/^[A-Za-z][A-Za-z .'-]*$/"],
            "gender" => ["nullable", "in:male,female,other"],
            "email" => ["required", "string", "email:rfc", "max:255", "unique:users,email," . $user->id],
            "pincode" => ["required", "digits:6", "regex:/^[1-9][0-9]{5}$/"],
            "city" => ["required", "string", "min:2", "max:100", "regex:/^[A-Za-z][A-Za-z .'-]*$/"],
            "state_id" => ["required", "integer", "exists:states,id,status,0"],
            "profile_pic" => ["nullable", "file", "image", "mimes:jpg,jpeg,png", "max:2048"],
        ], [
            "name.required" => "Please enter your full name.",
            "name.regex" => "Name can only contain letters, spaces, dots and hyphens.",
            "gender.in" => "Please choose a valid gender.",
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
            "profile_pic.image" => "Profile photo must be an image.",
            "profile_pic.mimes" => "Profile photo must be a JPG or PNG.",
            "profile_pic.max" => "Profile photo must be 2 MB or smaller.",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => ["message" => [$validator->errors()->first()]]], 422);
        }

        $city = trim((string) $request->input("city"));
        $state_id = (int) $request->input("state_id");

        $update = [
            "name" => trim((string) $request->input("name")),
            "gender" => $request->input("gender") ?: null,
            "email" => strtolower(trim((string) $request->input("email"))),
            "pincode" => (string) $request->input("pincode"),
            "city" => $city,
            "state_id" => $state_id,
            "city_id" => $this->cityId($state_id, $city),
            "updated_at" => now(),
        ];

        if ($request->hasFile("profile_pic")) {
            $file = $request->file("profile_pic");
            $name = "profile_" . $user->id . "_" . now()->format("YmdHis") . "_" . random_int(1000, 9999) . "." . strtolower($file->getClientOriginalExtension());
            $dir = public_path("uploads");
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $file->move($dir, $name);

            if (!empty($user->profile_pic) && str_starts_with($user->profile_pic, "profile_") && is_file($dir . DIRECTORY_SEPARATOR . $user->profile_pic)) {
                @unlink($dir . DIRECTORY_SEPARATOR . $user->profile_pic);
            }
            $update["profile_pic"] = $name;
        }

        DB::table("users")->where("id", $user->id)->update($update);

        return response()->json([
            "message" => "Profile updated",
            "step" => route("_userProfile"),
        ], 200);
    }

    private function cityId(int $state_id, string $city): ?int
    {
        $id = DB::table("districts")
            ->where("state_id", $state_id)
            ->where("status", 0)
            ->whereRaw("LOWER(name) = ?", [strtolower($city)])
            ->value("id");

        if (!$id) {
            $id = DB::table("districts")
                ->where("state_id", $state_id)
                ->where("status", 0)
                ->where("name", "like", $city . "%")
                ->orderBy("name")
                ->value("id");
        }

        return $id ? (int) $id : null;
    }

    public function supportIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        if ("POST" === $request->method()) {
            return $this->supportPost($request, $user);
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

    private function supportPost(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            "reason_id" => ["required", "integer", "exists:support_reasons,id,status,1"],
            "message" => ["required", "string", "min:10", "max:1000"],
        ], [
            "reason_id.required" => "Please select a reason.",
            "reason_id.exists" => "Please select a valid reason.",
            "message.required" => "Please describe your issue.",
            "message.min" => "Please describe your issue in at least 10 characters.",
            "message.max" => "Message can be at most 1000 characters.",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => ["message" => [$validator->errors()->first()]]], 422);
        }

        $today = DB::table("support_tickets")
            ->where("user_id", $user->id)
            ->whereDate("created_at", now()->toDateString())
            ->count();
        if ($today >= 5) {
            return response()->json(["errors" => ["message" => ["You have raised 5 tickets today. Please wait for our reply or try again tomorrow."]]], 422);
        }

        do {
            $ticket_no = "ST-" . now()->format("Ymd") . "-" . strtoupper(\Illuminate\Support\Str::random(6));
        } while (DB::table("support_tickets")->where("ticket_no", $ticket_no)->exists());

        DB::table("support_tickets")->insert([
            "ticket_no" => $ticket_no,
            "user_id" => $user->id,
            "reason_id" => (int) $request->input("reason_id"),
            "message" => trim((string) $request->input("message")),
            "priority" => "medium",
            "status" => "open",
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        return response()->json([
            "message" => "Ticket " . $ticket_no . " raised. We will get back within one working day.",
            "step" => route("_userSupport"),
        ], 200);
    }

    public function notificationsIndex(Request $request)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        if ("POST" === $request->method()) {
            return $this->notificationsPost($request, $user);
        }

        return $this->panelView($request, "notificationsIndex", $user, [
            "notifications" => DB::table("notifications")->where("user_id", $user->id)->orderByDesc("id")->limit(50)->get(),
        ]);
    }

    private function notificationsPost(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            "notification_id" => ["nullable", "integer"],
            "mark_all" => ["nullable", "boolean"],
        ]);

        if ($validator->fails() || (!$request->boolean("mark_all") && !$request->filled("notification_id"))) {
            return response()->json(["errors" => ["message" => ["Nothing to mark as read."]]], 422);
        }

        $query = DB::table("notifications")->where("user_id", $user->id)->where("is_read", 0);
        if (!$request->boolean("mark_all")) {
            $query->where("id", (int) $request->input("notification_id"));
        }
        $updated = $query->update(["is_read" => 1, "updated_at" => now()]);

        return response()->json([
            "message" => $request->boolean("mark_all") ? "All notifications marked as read" : ($updated ? "Marked as read" : "Already read"),
            "step" => route("_userNotifications"),
        ], 200);
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

    public function deleteAccountIndex(Request $request, OtpService $otp_service)
    {
        [$user, $fail] = $this->requireUser($request);
        if ($fail) {
            return $fail;
        }

        if ("POST" === $request->method()) {
            return $this->deleteAccountPost($request, $user, $otp_service);
        }

        $otp = session(self::SESSION_KEY . ".delete_otp", []);
        $cooldown = 0;
        if (!empty($otp["sent_at"])) {
            $elapsed = (int) Carbon::parse($otp["sent_at"])->diffInSeconds(now());
            $cooldown = max(0, (int) config("web.sms.otp.cooldown_seconds", 60) - $elapsed);
        }

        return $this->panelView($request, "deleteAccountIndex", $user, [
            "otp_sent" => !empty($otp["sent_at"]),
            "cooldown" => $cooldown,
        ]);
    }

    private function deleteAccountPost(Request $request, $user, OtpService $otp_service)
    {
        if ("send" === $request->input("action")) {
            if (!$request->boolean("confirm")) {
                return response()->json(["errors" => ["message" => ["Please tick the box to confirm you understand this cannot be undone."]]], 422);
            }

            $sent = $otp_service->issue((string) $user->phone);
            if (true !== $sent["status"]) {
                return response()->json(["errors" => ["message" => [$sent["message"]]]], 422);
            }

            session()->put(self::SESSION_KEY . ".delete_otp", ["sent_at" => now()->toDateTimeString()]);

            return response()->json([
                "message" => $sent["message"],
                "cooldown" => $sent["cooldown"] ?? (int) config("web.sms.otp.cooldown_seconds", 60),
                "cooldown_target" => "#fl-resend-btn",
                "show" => "#fl-otp-wrap",
                "hide" => "#fl-send-wrap",
                "focus" => "#otp",
            ], 200);
        }

        $pending = session(self::SESSION_KEY . ".delete_otp", []);
        if (empty($pending["sent_at"])) {
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

        $check = $otp_service->verify((string) $user->phone, (string) $request->input("otp"));
        if (true !== $check["status"]) {
            if (!empty($check["burned"])) {
                session()->forget(self::SESSION_KEY . ".delete_otp");
                return response()->json([
                    "errors" => ["message" => [$check["message"]]],
                    "step" => route("_userDeleteAccount"),
                ], 422);
            }

            $max_attempts = (int) config("web.sms.otp.max_attempts", 5);
            $attempts = (int) ($pending["attempts"] ?? 0) + 1;

            if ($attempts >= $max_attempts) {
                DB::table("otp_logs")->where("phone", $user->phone)->where("is_used", 0)->update(["is_used" => 1, "updated_at" => now()]);
                session()->forget(self::SESSION_KEY . ".delete_otp");

                return response()->json([
                    "errors" => ["message" => ["Too many wrong attempts. Please request a new OTP."]],
                    "step" => route("_userDeleteAccount"),
                ], 422);
            }

            session()->put(self::SESSION_KEY . ".delete_otp.attempts", $attempts);
            $left = $max_attempts - $attempts;

            return response()->json([
                "errors" => ["message" => [$check["message"] . " " . $left . " attempt" . (1 == $left ? "" : "s") . " left."]],
            ], 422);
        }

        DB::transaction(function () use ($user) {
            DB::table("users")->where("id", $user->id)->update([
                "phone" => $user->id . "_D_" . $user->phone,
                "email" => $user->email ? "del" . $user->id . "_" . $user->email : null,
                "status" => 3,
                "fcm_token" => null,
                "updated_at" => now(),
            ]);
            DB::table("personal_access_tokens")->where("tokenable_id", $user->id)->delete();
        });

        session()->forget(self::SESSION_KEY);
        $request->session()->regenerate();
        session()->flash("success", "Your account has been deleted. You are welcome back any time.");

        return response()->json([
            "message" => "Account deleted",
            "step" => route("_homeIndex"),
        ], 200);
    }
}
