<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\State;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


class ApplicationsController extends Controller
{

    public function postIndex(Request $request)
    {
        $type = $request->input('type') ?: $request->route('type') ?: 'personal';
        $login_type = $request->input('login_type') ?: $request->route('login_type');

        if ($request->ajax()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
            $query = DB::table("loan_applications")->select([
                "loan_applications.id as app_id",
                "loan_applications.application_no as uuid",
                "loan_applications.applied_at as rec_date",
                "loan_applications.eligible_amount as loan_amount",
                "loan_applications.monthly_income as income",
                "loan_applications.existing_emi as emi_paying",
                DB::raw("'No' as emi_bounce"),
                "loan_applications.tenure_months as loantenure",
                "loan_applications.status",
                "loan_status.label as status_label",
                DB::raw("'primary' as status_class"),
                "loan_types.label as loan_types",
                "cibil_scores.label as cibil_scores",
                "loan_purposes.label as loan_purposes",
                "users.uuid as u_uuid",
                "users.name as u_name",
                "users.phone as u_phone",
                "users.email as u_email",
                "users.city as u_city",
                "states.name as u_state",
                DB::raw("COALESCE(NULLIF(loan_applications.pincode, ''), users.pincode) as u_pincode"),
                "loan_applications.credit_card_usage",
            ])->Join("users", function ($join) {
                $join->on("users.id", "=", "loan_applications.user_id");
            })->leftJoin("states", function ($join) {
                $join->on("states.id", "=", "users.state_id");
            })->leftJoin("loan_status", function ($join) {
                $join->on("loan_status.id", "=", "loan_applications.status");
            })->leftJoin("loan_types", function ($join) {
                $join->on("loan_types.id", "=", "loan_applications.loan_type_id");
            })->leftJoin("cibil_scores", function ($join) {
                $join->on("cibil_scores.id", "=", "loan_applications.cibil_score");
            })->leftJoin("loan_purposes", function ($join) {
                $join->on("loan_purposes.id", "=", "loan_applications.loan_purpose_id");
            })
                ->when($fdate, fn ($q) => $q->where("loan_applications.applied_at", ">=", $fdate))
                ->when($tdate, fn ($q) => $q->where("loan_applications.applied_at", "<=", $tdate));

            $typeMap = [
                'personal' => 1,
                'business' => 2,
                'credit-card' => 3,
            ];

            $type_id = $typeMap[$type] ?? 1;
            $query->where("loan_applications.loan_type_id", $type_id);

            if (!empty($login_type)) {
                $query->where("loan_applications.login_type", $login_type);
            }

            $table_data = $query->orderBy("loan_applications.id", "desc")->get();
            /* $sql = vsprintf(
                str_replace('?', "'%s'", $query->toSql()),
                $query->getBindings()
            );

            dd($sql); */
            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("status", function ($row) {
                    $html = '<span class="badge bg-label-' . $row->status_class . '"> ' . $row->status_label . ' </span>';
                    return $html;
                })
                ->addColumn("rec_date", function ($row) {
                    return !empty($row->rec_date) ? date("d M Y", strtotime($row->rec_date)) : "-";
                })
                ->addColumn("rec_time", function ($row) {
                    return !empty($row->rec_date) ? date("h:i A", strtotime($row->rec_date)) : "-";
                })
                ->addColumn("u_name", function ($row) {
                    return !empty($row->u_name) ? ucfirst($row->u_name) : "-";
                })
                ->addColumn("phone", function ($row) {
                    return !empty($row->u_phone) ? $row->u_phone : "-";
                })
                ->addColumn("email", function ($row) {
                    return !empty($row->u_email) ? $row->u_email : "-";
                })
                ->addColumn("state", function ($row) {
                    return !empty($row->u_state) ? $row->u_state : "-";
                })
                ->addColumn("city", function ($row) {
                    return !empty($row->u_city) ? $row->u_city : "-";
                })
                ->addColumn("pincode", function ($row) {
                    return !empty($row->u_pincode) ? $row->u_pincode : "-";
                })
                ->addColumn("loan_purposes", function ($row) {
                    return !empty($row->loan_purposes) ? $row->loan_purposes : "-";
                })
                ->addColumn("cibil_scores", function ($row) {
                    return !empty($row->cibil_scores) ? $row->cibil_scores : "-";
                })
                ->addColumn("loan_amount", function ($row) {
                    return ($row->loan_amount === null || $row->loan_amount === "") ? "-" : $row->loan_amount;
                })
                ->addColumn("income", function ($row) {
                    return ($row->income === null || $row->income === "") ? "-" : $row->income;
                })
                ->addColumn("loantenure", function ($row) {
                    return ($row->loantenure === null || $row->loantenure === "") ? "-" : $row->loantenure;
                })
                ->addColumn("current_emi", function ($row) {
                    return ($row->emi_paying === null || $row->emi_paying === "") ? "-" : $row->emi_paying;
                })
                ->addColumn("emi_bounce", function ($row) {
                    return !empty($row->emi_bounce) ? $row->emi_bounce : "-";
                })
                ->addColumn("credit_card_usage", function ($row) {
                    return $row->credit_card_usage == 1 ? "Yes" : "No";
                })

                ->addColumn("action", function ($row) {
                    return $this->getTableActionHtml([
                        "user" => route("_customersEdit", [
                            "key" => $row->u_uuid
                        ]),
                        "view" => route("_applicationView", [
                            "key" => $row->uuid
                        ])
                    ]);
                })
                ->rawColumns([
                    "status",
                    "action",
                ])
                ->make(true);
        }
        $type_name = $type;
        if ($type == 'credit-card') {
            $type_name = 'Credit Card';
            $login_type = '';
        } else if ($type == 'personal') {
            $type_name = 'Personal Loan';
        } else if ($type == 'business') {
            $type_name = 'Business Loan';
        }

        return view("backend.applicationsIndex", [
            "type" => $type,
            "type_name" => $type_name,
            "login_type" => $login_type
        ]);
    }

    public function viewPostIndex(string $id = null, Request $request)
    {
        Validator::make($request->all(), [
            "uuid" => "required|exists:loan_applications,application_no",
        ])->validate();

        $table_data = DB::table("loan_applications")->select([
            "loan_applications.id as app_id",
            "loan_applications.application_no as uuid",
            "loan_applications.user_id",
            "loan_applications.applied_at as rec_date",
            "loan_applications.eligible_amount as loan_amount",
            "loan_applications.monthly_income as income",
            "loan_applications.existing_emi as emi_paying",
            "loan_applications.tenure_months as loantenure",
            "loan_applications.status",
            "loan_applications.created_at",
            "loan_applications.updated_at",
            "loan_applications.pincode as u_pincode",
            "loan_applications.aadhar_number",
            "loan_applications.aadhar_front",
            "loan_applications.aadhar_back",
            "loan_applications.pan_number",
            "loan_applications.pan_front",
            "loan_applications.selfie",
            "loan_applications.business_type",
            "loan_applications.business_age",
            "loan_applications.business_identity_proof",
            "loan_applications.bank_account",
            "loan_applications.annual_business_turnover",
            "loan_applications.employment_type",
            "loan_applications.marital_status",
            "loan_applications.job_type",
            "loan_applications.work_experience",
            "loan_applications.credit_card_usage",
            "loan_applications.bank_name",
            "loan_applications.bank_branch",
            "loan_applications.ifsc_code",
            "loan_applications.account_number",
            "loan_applications.login_type",
            "loan_applications.self_login_bank_id",
            "loan_applications.payment_status",
            "loan_applications.is_converted",
            "loan_status.label as status_label",
            "loan_types.id as loan_type_id",
            DB::raw("'No' as emi_bounce"), // Fixed missing column
            DB::raw("'primary' as status_class"),
            "loan_types.label as loan_types",
            "cibil_scores.label as cibil_scores",
            "loan_purposes.label as loan_purposes",
            "users.created_at as u_created_at",
            "users.uuid as u_uuid",
            "users.name as u_name",
            "users.phone as u_phone",
            "users.email as u_email",
            "users.city as u_city",
            "states.name as u_state",
            DB::raw("IF(loan_applications.credit_card_usage = 1, 'Yes', 'No') as credit_card_usage"),
            DB::raw("IF(loan_applications.employment_type = 'salaried','Salaried Person','Self Employed Person') as user_type_label"),
        ])->Join("users", function ($join) {
            $join->on("users.id", "=", "loan_applications.user_id");
        })->leftJoin("states", function ($join) {
            $join->on("states.id", "=", "users.state_id")
                ->where("states.status", "0");
        })->leftJoin("loan_status", function ($join) {
            $join->on("loan_status.id", "=", "loan_applications.status");
        })->leftJoin("loan_types", function ($join) {
            $join->on("loan_types.id", "=", "loan_applications.loan_type_id");
        })->leftJoin("cibil_scores", function ($join) {
            $join->on("cibil_scores.id", "=", "loan_applications.cibil_score");
        })->leftJoin("loan_purposes", function ($join) {
            $join->on("loan_purposes.id", "=", "loan_applications.loan_purpose_id");
        })
            ->where("loan_applications.application_no", $request->uuid)
            ->orderBy("loan_applications.id", "desc")->first();

        $loan_status = collect(DB::table('loan_status')->where("status", 1)->get())->toArray();

        $application_history = collect(DB::table('application_history')->select([
            "application_history.*",
            "loan_status.label",
        ])->leftJoin("loan_status", function ($join) {
            $join->on("loan_status.id", "=", "application_history.status_id");
        })
            ->where("application_history.application_id", $table_data->app_id ?? 0)
            ->orderBy("application_history.id", "desc")
            ->get())->toArray();

        return view("backend.applicationViewIndex", [
            "data" => $table_data,
            "loan_status" => $loan_status,
            "application_history" => $application_history,
        ]);
    }

    public function addStatusPost(string $id = null, Request $request)
    {
        if (!$request->ajax() || "POST" != $request->method()) {
            return response()->json([
                "errors" => [
                    "message" => ["something went wrong please try again"]
                ]
            ], 422);
        }

        Validator::make($request->all(), [
            "id" => "required|exists:loan_applications,id",
            "status" => "required|exists:loan_status,id",
            #"remarks" => "required"
        ])->validate();

        $application_status_history_id = DB::table("application_history")->insertGetId([
            "application_id" => $request->id,
            "remarks" => $request->remarks ?? "",
            "status_id" => $request->status,
            "created_at" => $this->currentDataTime(),
        ]);

        DB::table("loan_applications")->where("id", $request->id)->update([
            "status" => $request->status,
            "updated_at" => $this->currentDataTime(),
        ]);

        $table_data = DB::table("loan_applications")->select([
            "loan_applications.id",
            "users.name as u_name",
            "users.phone as u_phone",
            "users.email as u_email"
        ])->Join("users", function ($join) {
            $join->on("users.id", "=", "loan_applications.user_id");
        })->where("loan_applications.application_no", $request->uuid)->first();

        $phone = $table_data->u_phone ?? "";
        $remarks = $request->remarks ?? "";
        if (!empty($phone) && !empty($remarks)) {
            $this->sendRemarkStatus($phone, $remarks);
        }

        return response()->json(["message" => "application status successfully changed."], 200);
    }

    public function sendRemarkStatus() {}

    public function removeDocumentPost(Request $request)
    {
        if (!$request->ajax() || "POST" != $request->method()) {
            return response()->json([
                "message" => "something went wrong please try again"
            ], 422);
        }

        Validator::make($request->all(), [
            "id" => "required|exists:loan_applications,id",
            "document_field" => "required|in:aadhar_front,aadhar_back,pan_front,selfie,business_identity_proof",
        ])->validate();

        DB::table("loan_applications")->where("id", $request->id)->update([
            $request->document_field => null,
            "updated_at" => $this->currentDataTime(),
        ]);

        return response()->json(["message" => "application document successfully removed."], 200);
    }
}
