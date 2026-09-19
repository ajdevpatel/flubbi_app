<?php

namespace App\Http\Controllers\Fend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function userDashboardIndex(Request $request)
    {
        if (1 != Auth::user()->i_agree) {
            return redirect()->route("_licenseAgreementIndex")->with("error", "please accept the terms of the ageement, click I Agree to continue.");
        }

        return view("frontend.users.dashboardIndex");
    }

    ##################################################################


    public function licenseAgreementIndex(Request $request)
    {
        if ("POST" == $request->method()) {
            Validator::make($request->all(), [
                "i_agree" => "required|in:1"
            ])->validate();
            DB::table("users")->where("id", auth::user()->id)->update([
                "i_agree" => "1"
            ]);
            return redirect()->route("_userDashboardIndex");
        }

        return view("frontend.users.licenseAgreementIndex");
    }

    public function userRaiseRequestIndex(Request $request)
    {
        if (1 != Auth::user()->i_agree) {
            return redirect()->route("_licenseAgreementIndex")->with("error", "please accept the terms of the ageement, click I Agree to continue.");
        }

        if ($request->ajax() && "POST" === $request->method()) {
            Validator::make($request->all(), [
                "req_reason" => "required|exists:support_reasons,id",
                "req_message" => "required"
            ])->validate();

            $check_req = collect(DB::table("support_tickets")->whereDate("created_at", date("Y-m-d"))->where("user_id", auth::user()->id)->get())->toArray();
            if ([] == $check_req) {
                $in_qry = DB::table("support_tickets")->insert([
                    "uuid" => $this->generateUUID(),
                    "user_id" => auth::user()->id,
                    "auto_number" => time(),
                    "name" => auth::user()->name,
                    "phone" => auth::user()->phone,
                    "mail" => auth::user()->email,
                    "reason_id" => $request->req_reason,
                    "description" => $request->req_message,
                    "ip_address" => $request->ip(),
                    "status" => "0",
                    "deleted" => "0",
                    "created_at" => $this->currentDataTime(),
                ]);
                return response()->json(["message" => "Thank you for your request, it has been submitted successfully and will be answered as soon as possible."], 200);
            }
            return response()->json(["message" => "Thank you for your request, it has been submitted successfully and will be answered as soon as possible."], 200);
        }

        $support_reasons = collect(DB::table("support_reasons")->where([
            "support_reasons.deleted" => "0",
        ])->orderBy("support_reasons.priority", "asc")->get())->toArray();

        return view("frontend.users.raiseRequestIndex", [
            "support_reasons" => $support_reasons
        ]);
    }

    public function userDocumentsIndex(Request $request)
    {
        if (1 != Auth::user()->i_agree) {
            return redirect()->route("_licenseAgreementIndex")->with("error", "please accept the terms of the ageement, click I Agree to continue.");
        }

        if ($request->ajax() && "POST" === $request->method()) {
            Validator::make($request->all(), [
                "doc" => "required|file|max:5000|mimes:jpg,jpeg,png,doc,docx,pdf",
                "type" => "required|in:profilephoto,aadharcard,pancard,cancelcheque,lightbill,addressproof,bankstatement,formsixteen,salaryslip,businessproof,itreturn"
            ])->validate();

            $type = $request->type;
            $in_data = [
                "updated_by" => auth::user()->id,
                "updated_at" => $this->currentDataTime(),
            ];
            if ($request->has("aadharcard_number")) {
                Validator::make($request->all(), [
                    "aadharcard_number" => "required|numeric|digits:16",
                ])->validate();
                $in_data["aadharcard_number"] = $request->aadharcard_number;
            }
            if ($request->has("pancard_number")) {
                Validator::make($request->all(), [
                    "pancard_number" => "required|string|min:10|max:10",
                ])->validate();
                $in_data["pancard_number"] = $request->pancard_number;
            }

            if ($request->has("doc") && !empty($request->doc)) {
                $obj = $request->doc;
                $fileType = $obj->getClientOriginalExtension();
                $logo_img = $type . "_" . time() . "." . $fileType;
                $file_obj = $request->file("doc")->move(public_path(config("web.webapp.upload_dir") . auth::user()->doc_dir), $logo_img);
                $in_data[$type] = $logo_img;
                $in_data[$type . "_verify"] = "0";
            }
            if (DB::table("user_documents")->where("user_id", auth::user()->id)->exists()) {
                DB::table("user_documents")->where("user_id", auth::user()->id)->update($in_data);
            } else {
                $in_data["uuid"] = $this->generateUUID();
                $in_data["user_id"] = auth::user()->id;
                DB::table("user_documents")->where("user_id", auth::user()->id)->insertGetId($in_data);
            }
            return response()->json(["message" => "Your documents have been successfully submitted. ."], 200);
        }

        $documents_list = DB::table("user_documents")->where([
            "user_documents.user_id" => auth::user()->id,
        ])->orderBy("user_documents.id", "asc")->first();

        return view("frontend.users.documentsIndex", [
            "data" => $documents_list
        ]);
    }

    public function userApplicationListIndex(Request $request)
    {
        if (1 != Auth::user()->i_agree) {
            return redirect()->route("_licenseAgreementIndex")->with("error", "please accept the terms of the ageement, click I Agree to continue.");
        }

        $application_list = collect(DB::table("loan_applications")->select([
            "loan_applications.id as p_id",
            "loan_applications.uuid",
            "loan_applications.rec_date",
            "loan_applications.loan_amount",
            "loan_applications.income",
            "loan_applications.emi_paying",
            DB::raw("IF(loan_applications.emi_bounce = '1','Yes','No') as emi_bounce"),
            "loan_applications.loantenure",
            "loan_applications.status",
            "loan_status.label as status_label",
            "loan_status.class as status_class",
            "loan_types.label as loan_types",
            "cibil_scores.label as cibil_scores",
            "loan_purposes.label as loan_purposes",
        ])->leftJoin("loan_status", function ($join) {
            $join->on("loan_status.id", "=", "loan_applications.status");
        })->leftJoin("loan_types", function ($join) {
            $join->on("loan_types.id", "=", "loan_applications.type_id");
        })->leftJoin("cibil_scores", function ($join) {
            $join->on("cibil_scores.id", "=", "loan_applications.cibil_score_id");
        })->leftJoin("loan_purposes", function ($join) {
            $join->on("loan_purposes.id", "=", "loan_applications.loan_purposes");
        })
            ->where("loan_applications.user_id", auth::user()->id)
            ->where("loan_applications.deleted", "0")
            ->orderBy("loan_applications.id", "desc")
            ->get())->toArray();

        if ([] != collect($application_list)->toArray()) {
            foreach ($application_list as $k => $v) {
                $v->loan_amount = "₹" . $this->amountFormatIndia($v->loan_amount) . "/-";
                $v->income = "₹" . $this->amountFormatIndia($v->income) . "/-";
            }
        }
        #dd($application_list);

        return view("frontend.users.applicationListIndex", [
            "application_list" => $application_list
        ]);
    }

    public function userProfileIndex(Request $request)
    {
        if (1 != Auth::user()->i_agree) {
            return redirect()->route("_licenseAgreementIndex")->with("error", "please accept the terms of the ageement, click I Agree to continue.");
        }

        if ($request->ajax() && "POST" === $request->method()) {
            Validator::make($request->all(), [
                "name" => "required",
                "mail" => "required|email",
                "city" => "required",
                "state" => "required|numeric|exists:states,id",
                "pincode" => "required|numeric|digits:6",
                "opassword" => "",
                "password" => "required_if:opassword,!=,''",
                "cpassword" => "required_if:opassword,!=,''|same:password",
            ], [
                "id.*" => "user account does not exist in please user credentials.",
                "cpassword.*" => "password and confirm password do not match",
            ])->validate();

            if (auth::user()->email != $request->mail) {
                Validator::make($request->all(), [
                    "mail" => "required|email|unique:users,email",
                ])->validate();
            }

            $in_data = [
                "name" => $request->name,
                "email" => $request->mail,
                "city" => $request->city,
                "state_id" => $request->state,
                "pincode" => $request->pincode,
                "updated_by" => $this->getLoginUserId(),
                "updated_at" => $this->currentDataTime(),
            ];
            if ($request->has("opassword") && "" != $request->opassword) {
                Validator::make($request->all(), [
                    "password" => "required|min:8",
                    "cpassword" => "required|min:8|same:password",
                ], [
                    "cpassword.*" => "password and confirm password do not match",
                ])->validate();

                if (Hash::check($request->opassword, Auth::user()->password)) {
                    $in_data["password"] = Hash::make($request->password);
                } else {
                    return response()->json([
                        "errors" => [
                            "message" => ["Current Password does not match with Old Password."]
                        ]
                    ], 422);
                }
            }
            DB::table("users")->where("uuid", auth::user()->uuid)
                ->where("id", auth::user()->id)
                ->update($in_data);
            return response()->json(["message" => "Profile updated successfully."], 200);
        }

        $state_index = collect(DB::table("states")->where("country_id", 101)
            ->where("status", "0")
            ->where("deleted", "0")
            ->orderBy("name", "asc")
            ->get())->toArray();

        return view("frontend.users.profileIndex", [
            "state_index" => $state_index
        ]);
    }

    public function userSubscriptionsIndex(Request $request)
    {
        if (1 != Auth::user()->i_agree) {
            return redirect()->route("_licenseAgreementIndex")->with("error", "please accept the terms of the ageement, click I Agree to continue.");
        }

        $subscriptions = DB::table("user_subscriptions")->select([
            "user_subscriptions.id as p_id",
            "user_subscriptions.uuid",
            "user_subscriptions.rec_date",
            "user_subscriptions.card_number",
            "user_subscriptions.status",
            "transactions.transaction_id",
            "transactions.status as tz_status",
            DB::raw("IF(user_subscriptions.is_manual = '1','Yes','No') as is_manual"),
            DB::raw("ROUND(IFNULL(user_subscriptions.amount,0), 2) as amount"),
            DB::raw("DATE_FORMAT(start_date, '%d-%m-%Y') as start_date"),
            DB::raw("DATE_FORMAT(expiry_date, '%d-%m-%Y') as expiry_date"),
            "users.name",
            "loan_types.card_heading as loan_type_heading",
        ])->leftJoin("users", function ($join) {
            $join->on("users.id", "=", "user_subscriptions.user_id");
        })->leftJoin("loan_applications", function ($join) {
            $join->on("loan_applications.id", "=", "user_subscriptions.application_id");
        })->leftJoin("loan_types", function ($join) {
            $join->on("loan_types.id", "=", "loan_applications.type_id");
        })->leftJoin("transactions", function ($join) {
            $join->on("transactions.object_id", "=", "user_subscriptions.id")->where("object_type", "0");
        })
            ->where("user_subscriptions.user_id", auth::user()->id)
            ->where("user_subscriptions.deleted", "0")
            ->orderBy("user_subscriptions.id", "desc")
            ->get();

        dd(collect($subscriptions)->toArray());

        return view("frontend.users.subscriptionsIndex", [
            "subscription_list" => $subscriptions
        ]);
    }
}
