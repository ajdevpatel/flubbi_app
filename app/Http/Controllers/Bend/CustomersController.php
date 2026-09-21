<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class CustomersController extends Controller
{
    public function postIndex(Request $request, $type = null, $login_type = null)
    {
        $type = $request->input('type', $type);
        $login_type = $request->input('login_type', $login_type);

        if ($request->ajax()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
            $query = DB::table("users")->select([
                "users.uuid",
                "users.name",
                "users.phone",
                "users.email",
                "users.city",
                "users.status",
                "users.pincode",
                "users.created_at",
                "states.name as state_name",
            ])->leftJoin("states", function ($join) {
                $join->on("states.id", "=", "users.state_id");
            })->where("users.role", "2")
                ->whereNull("users.deleted_at")
                ->when($fdate, fn ($q) => $q->where("users.created_at", ">=", $fdate))
                ->when($tdate, fn ($q) => $q->where("users.created_at", "<=", $tdate));

            if ($type && $type !== 'all') {
                $typeMap = [
                    'personal' => 1,
                    'business' => 2,
                    'credit-card' => 3,
                ];
                $type_id = $typeMap[$type] ?? 1;

                $query->whereExists(function ($q) use ($type_id, $login_type) {
                    $q->select(DB::raw(1))
                        ->from('loan_applications')
                        ->whereColumn('loan_applications.user_id', 'users.id')
                        ->where('loan_applications.loan_type_id', $type_id);
                    if ($login_type) {
                        $q->where('loan_applications.login_type', $login_type);
                    }
                });
            }

            $table_data = $query->orderBy("users.id", "desc")->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("created_at", function ($row) {
                    return $row->created_at ? "<b>" . date("d M Y", strtotime($row->created_at)) . "</b>" : "-";
                })
                ->addColumn("created_time", function ($row) {
                    return $row->created_at ? date("h:i A", strtotime($row->created_at)) : "-";
                })
                ->addColumn("status", function ($row) {
                    return $this->getTableStatusHtml($row->status, [
                        "pending" => "0",
                        "active" => "1",
                        "deactive" => "2"
                    ]);
                })
                ->addColumn("full_name", function ($row) {
                    return $row->name ? "<b>" . ucfirst($row->name) . "</b>" : "-";
                })
                ->addColumn("phone", function ($row) {
                    return $row->phone ?: "-";
                })
                ->addColumn("email", function ($row) {
                    return $row->email ?: "-";
                })
                ->addColumn("state", function ($row) {
                    return $row->state_name ?: "-";
                })
                ->addColumn("city", function ($row) {
                    return $row->city ?: "-";
                })
                ->addColumn("pincode", function ($row) {
                    return $row->pincode ?: "-";
                })
                ->addColumn("action", function ($row) {
                    return $this->getTableActionHtml([
                        "edit" => route('_customersEdit', [
                            "key" => $row->uuid
                        ]),
                        "delete" => route('_customersDelete', [
                            "key" => $row->uuid
                        ])
                    ]);
                })
                ->rawColumns(["created_at", "status", "full_name", "action"])
                ->make(true);
        }
        $type_name = $type;
        if ($type == 'all') {
            $type_name = 'All';
            $login_type = '';
        } else if ($type == 'personal') {
            $type_name = 'Personal Loan';
        } else if ($type == 'business') {
            $type_name = 'Business Loan';
        }
        return view("backend.customersIndex", [
            "type" => $type,
            "login_type" => $login_type,
            "type_name" => $type_name,
        ]);
    }

    public function postEdit(string $id = null, Request $request)
    {
        $table_data = DB::table("users")->select([
            "users.id as p_id",
            "users.uuid",
            "users.name",
            "users.phone",
            "users.email",
            "users.city",
            "users.pincode",
            "users.pan_card",
            "users.created_at",
            "users.updated_at",
            "users.status",
            "users.state_id",
        ])->where("users.role", "2")
            ->whereNull("users.deleted_at")
            ->where("users.uuid", $id)
            ->first();

        $state_list = DB::table("states")->select([
            "id",
            "name"
        ])->orderBy("name", "asc")
            ->get();

        $application_list = DB::table("loan_applications")->select([
            "loan_applications.id as p_id",
            "loan_applications.application_no",
            "loan_applications.eligible_amount as loan_amount",
            "loan_applications.monthly_income as income",
            "loan_applications.existing_emi as emi_paying",
            "loan_applications.tenure_months as loantenure",
            "loan_applications.status",
            "loan_applications.created_at",
            "loan_status.label as status_label",
            "loan_types.label as loan_types",
            "loan_purposes.label as loan_purposes",
        ])->leftJoin("loan_status", function ($join) {
            $join->on("loan_status.id", "=", "loan_applications.status");
        })->leftJoin("loan_types", function ($join) {
            $join->on("loan_types.id", "=", "loan_applications.loan_type_id");
        })->leftJoin("loan_purposes", function ($join) {
            $join->on("loan_purposes.id", "=", "loan_applications.loan_purpose_id");
        })
            ->where("loan_applications.user_id", $table_data->p_id)
            ->orderBy("loan_applications.id", "desc")
            ->get();

        /*  $documents_list = DB::table("loan_documents")->where([
            "loan_documents.user_id" => $table_data->p_id,
        ])->orderBy("loan_documents.id", "asc")->first(); */

        return view("backend.customersEdit", [
            "state_list" => $state_list,
            "user" => $table_data,
            //"documents" => $documents_list,
            "application_list" => $application_list,
        ]);
    }

    public function postUpdate(string $id = null, Request $request)
    {
        if ($request->ajax() && "PUT" === $request->method()) {
            Validator::make($request->all(), [
                "uuid" => "required|exists:users,uuid",
                "p_id" => "required|numeric|exists:users,id",
                "name" => "required",
                "phone" => "required|numeric|digits:10",
                "pincode" => "required|numeric|digits:6",
                "mail" => "required|email",
                "city" => "required",
                "status" => "required|in:0,1,2",
                "state" => "required|numeric|exists:states,id",
                "password" => "",
                "cpassword" => "required_if:password,!=,''|same:password",
            ], [
                "id.*" => "user account does not exist in please user credentials.",
                "cpassword.*" => "password and confirm password do not match",
            ])->validate();

            $user_data = DB::table("users")->where("uuid", $request->uuid)->first();
            if ($user_data->email != $request->mail) {
                Validator::make($request->all(), [
                    "mail" => "required|email|unique:users,email",
                ])->validate();
            }
            if ($user_data->phone != $request->phone) {
                Validator::make($request->all(), [
                    "phone" => "required|unique:users,phone",
                ])->validate();
            }

            $in_data = [
                "name" => $request->name,
                "phone" => $request->phone,
                "email" => $request->mail,
                "city" => $request->city,
                "state_id" => $request->state,
                "pincode" => $request->pincode,
                "status" => $request->status,
                "updated_at" => $this->currentDataTime(),
            ];
            if ($request->has("password") && "" != $request->password) {
                Validator::make($request->all(), [
                    "password" => "required|min:8",
                    "cpassword" => "required|min:8|same:password",
                ], [
                    "cpassword.*" => "password and confirm password do not match",
                ])->validate();
                $in_data["password"] = Hash::make($request->password);

                $this->sendUserCredentials($request->p_id, $request->password);
            }
            DB::table("users")->where("uuid", $request->uuid)->where("id", $request->p_id)->update($in_data);
            return response()->json(["message" => "Profile updated successfully."], 200);
        }

        return response()->json([
            "errors" => [
                "message" => ["The request was unsuccessful. Please try again"]
            ]
        ], 422);
    }

    public function postDelete(string $id = null, Request $request)
    {
        if ($request->ajax() && "DELETE" === $request->method()) {
            $user_data = DB::table("users")->where("uuid", $id)->first();
            if ($user_data) {
                DB::table("loan_applications")->where("user_id", $user_data->id)->delete();
                DB::table("users")->where("uuid", $id)->delete();
                return response()->json(["message" => "Customer and their applications deleted successfully."], 200);
            }
            return response()->json([
                "errors" => [
                    "message" => ["User not found."]
                ]
            ], 422);
        }

        return response()->json([
            "errors" => [
                "message" => ["The request was unsuccessful. Please try again"]
            ]
        ], 422);
    }

    public function documentsUpdate(string $id = null, string $type = "", Request $request)
    {
        $request->merge([
            "type" => $type
        ]);
        Validator::make($request->all(), [
            "type" => "required|in:salary_slip,bank_statement,business_proof,gst_return,income_tax_eturn,utility_bill,cancel_cheque,other_one,other_two,other_three,other_four,other_five"
        ])->validate();

        $type_doc = $request->type;
        $doc = DB::table("loan_documents")->where("loan_documents.id", $id)->first();
        if ($doc) {
            DB::table("loan_documents")->where("loan_documents.id", $id)->update([
                $type => null,
                "status" => "0",
                "updated_at" => $this->currentDataTime(),
            ]);
            $u_uuid = DB::table("users")->where("users.id", $doc->user_id)->value("uuid");
            return redirect()->route("_customersEdit", ["key" => $u_uuid]);
        }
        return redirect()->back()->with('error', 'Document not found');
    }

    public function documentsVerifyStatusUpdate(string $id = null, Request $request)
    {
        $doc = DB::table("loan_documents")->where("loan_documents.id", $id)->first();
        if (!$doc) {
            return redirect()->back()->with('error', 'Document not found');
        }
        $u_uuid = DB::table("users")->where("users.id", $doc->user_id)->value("uuid");
        $in_data = [
            "status" => "0",
            "updated_at" => $this->currentDataTime(),
        ];
        if (1 != $doc->status) {
            $in_data["status"] = "1";
        }
        DB::table("loan_documents")->where("loan_documents.id", $id)->update($in_data);
        return redirect()->route("_customersEdit", ["key" => $u_uuid]);
    }

    public function dndPostIndex(Request $request)
    {
        if ($request->ajax()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;

            $table_data = DB::table("users")
                ->select(["users.uuid", "users.name", "users.phone", "users.email", "users.dnd_at"])
                ->where("users.is_dnd", 1)
                ->whereNull("users.deleted_at")
                ->when($fdate, fn ($q) => $q->where("users.dnd_at", ">=", $fdate))
                ->when($tdate, fn ($q) => $q->where("users.dnd_at", "<=", $tdate))
                ->orderBy("users.dnd_at", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("dnd_at", function ($row) {
                    return !empty($row->dnd_at) ? date("d M Y", strtotime($row->dnd_at)) : "-";
                })
                ->addColumn("name", function ($row) {
                    return !empty($row->name) ? ucwords($row->name) : "-";
                })
                ->addColumn("phone", function ($row) {
                    return $row->phone ?: "-";
                })
                ->addColumn("email", function ($row) {
                    return $row->email ?: "-";
                })
                ->addColumn("action", function ($row) {
                    return $this->getTableActionHtml([
                        "dnd_off" => route("_dndAddRemoveIndex", ["key" => $row->uuid]),
                    ]);
                })
                ->rawColumns(["action"])
                ->make(true);
        }

        return view("backend.dndCustomersIndex");
    }

    public function dndAddRemovePostIndex(string $id = null, Request $request)
    {
        if ("import-csv" === $id) {
            Validator::make($request->all(), [
                "dnd_csv" => "required|file|max:5120|mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel",
            ])->validate();

            $phones = [];
            $handle = fopen($request->file("dnd_csv")->getRealPath(), "r");
            while (false !== ($row = fgetcsv($handle))) {
                foreach ((array) $row as $cell) {
                    $clean = preg_replace("/\D/", "", (string) $cell);
                    if (10 === strlen($clean)) {
                        $phones[$clean] = true;
                    }
                }
            }
            fclose($handle);
            $phones = array_keys($phones);

            if ([] === $phones) {
                return response()->json([
                    "errors" => ["message" => ["No valid 10 digit mobile number found in the file."]],
                ], 422);
            }

            $updated = 0;
            foreach (array_chunk($phones, 1000) as $chunk) {
                $updated += DB::table("users")
                    ->whereIn("phone", $chunk)
                    ->where("is_dnd", 0)
                    ->update([
                        "is_dnd" => 1,
                        "dnd_at" => $this->currentDataTime(),
                        "updated_at" => $this->currentDataTime(),
                    ]);
            }

            return response()->json([
                "message" => $updated . " of " . count($phones) . " number(s) marked as DND.",
            ], 200);
        }

        $user = DB::table("users")->where("uuid", $id)->first();
        if (!$user) {
            return redirect()->back()->with("error", "Customer not found.");
        }

        DB::table("users")->where("uuid", $id)->update([
            "is_dnd" => 1 == $user->is_dnd ? 0 : 1,
            "dnd_at" => 1 == $user->is_dnd ? null : $this->currentDataTime(),
            "updated_at" => $this->currentDataTime(),
        ]);

        return redirect()->back();
    }

    public function postStore(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            Validator::make($request->all(), [
                "name" => "required|regex:/^[a-zA-Z ]+$/",
                "phone" => "required|numeric|unique:users,phone|digits:10",
                "mail" => "required|email|unique:users,email",
                "pincode" => "required|numeric|digits:6",
                "city" => "required",
                "state" => "required|numeric|exists:states,id",
                "password" => "required|min:8",
                "user_type" => "required|in:1,2",
                "loan_types" => "required|exists:loan_types,id,status,1",
                "loan_purposes" => "required|exists:loan_purposes,id,status,1",
                "cibil_scores" => "required|exists:cibil_scores,id,status,1",
                "loan_amount" => "required|numeric|min:1000",
                "income" => "required|numeric|min:1000",
                "emi_paying" => "required|numeric|min:0",
                "emi_tenure" => "required|in:12,24,36,48,60,72",
            ])->validate();

            try {
                DB::beginTransaction();
                $created_at = $this->currentDataTime();

                $user_id = DB::table("users")->insertGetId([
                    "uuid" => $this->generateUUID(),
                    "name" => $request->name,
                    "password" => Hash::make($request->password),
                    "phone" => $request->phone,
                    "pincode" => $request->pincode,
                    "email" => $request->mail,
                    "city" => $request->city,
                    "state_id" => $request->state,
                    "role" => 2,
                    "status" => 1,
                    "mobile_verified_at" => $created_at,
                    "created_at" => $created_at,
                    "updated_at" => $created_at,
                ]);

                DB::table("loan_applications")->insert([
                    "user_id" => $user_id,
                    "loan_type_id" => $request->loan_types,
                    "loan_purpose_id" => $request->loan_purposes,
                    "application_no" => $this->newApplicationNo(),
                    "name" => $request->name,
                    "email" => $request->mail,
                    "state_id" => $request->state,
                    "city" => $request->city,
                    "pincode" => $request->pincode,
                    "employment_type" => 2 == (int) $request->user_type ? "self_employed" : "salaried",
                    "monthly_income" => $request->income,
                    "existing_emi" => $request->emi_paying,
                    "cibil_score" => (int) $request->cibil_scores,
                    "tenure_months" => $request->emi_tenure,
                    "eligible_amount" => $request->loan_amount,
                    "eligibility_status" => "eligible",
                    "status" => 2,
                    "step" => 8,
                    "applied_at" => $created_at,
                    "created_at" => $created_at,
                    "updated_at" => $created_at,
                ]);

                $this->sendUserCredentials($user_id, $request->password);

                DB::commit();
                return response()->json(["message" => "Your customer has been successfully created."], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    "errors" => [
                        "message" => ["The request was unsuccessful. - " . $e->getMessage()]
                    ]
                ], 422);
            }
        }

        $state_index = collect(DB::table("states")
            ->orderBy("name", "asc")
            ->get())->toArray();

        $loan_type_index = collect(DB::table("loan_types")
            ->where("status", "1")
            ->get())->toArray();

        $purpose_index = collect(DB::table("loan_purposes")
            ->where("status", "1")
            ->get())->toArray();

        $cibil_score_index = collect(DB::table("cibil_scores")
            ->where("status", "1")
            ->get())->toArray();

        return view("backend.customersAdd", [
            "state_index" => $state_index,
            "purpose_index" => $purpose_index,
            "cibil_score_index" => $cibil_score_index,
            "loan_type_index" => $loan_type_index,
            "password" => $this->generatePassword(10),
        ]);
    }

    public function postInvoice(string $uuid = "", Request $request)
    {
        dd($uuid);
    }
}
