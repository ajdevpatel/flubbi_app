<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\State;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{

    public function transactionsPostIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = config("web.webapp.filter_from_date");
            $tdate = date("Y-m-d 00:00:00");
            if ($request->has("fdate") && !empty($request->fdate)) {
                $fdate = $request->fdate;
            }
            if ($request->has("tdate") && !empty($request->tdate)) {
                $tdate = $request->tdate . " 23:59:59";
            }

            $table_data = DB::table("transactions")->select([
                "transactions.id as p_id",
                "transactions.uuid",
                "transactions.rec_date",
                "transactions.created_at",
                "transactions.gateway_type",
                "transactions.transaction_id",
                "transactions.status",
                "transactions.description",
                DB::raw("IF(transactions.is_manual = '1','Yes','No') as is_manual"),
                DB::raw("ROUND(IFNULL(transactions.amount,0), 2) as tz_amount"),
                "loan_applications.uuid as app_uuid",
                "users.uuid as u_uuid",
                "users.name as u_name",
                "users.phone as u_phone",
                "users.email as u_email",
                "loan_types.label as loan_type_label",
                "user_subscriptions.card_number as sub_card_number",
            ])->leftJoin("users", function ($join) {
                $join->on("users.id", "=", "transactions.user_id");
            })->leftJoin("loan_applications", function ($join) {
                $join->on("loan_applications.id", "=", "transactions.application_id");
            })->leftJoin("loan_types", function ($join) {
                $join->on("loan_types.id", "=", "loan_applications.type_id");
            })->leftJoin("user_subscriptions", function ($join) {
                $join->on("user_subscriptions.id", "=", "transactions.object_id");
            })
                ->where("transactions.deleted", "0")
                ->whereBetween("transactions.created_at", [$fdate, $tdate])
                ->orderBy("transactions.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("rec_date", function ($row) {
                    return "<b>Rec. : </b>" . $row->rec_date . "<br> <b> Created : </b>" . $row->created_at . "<br><b> Is Manual : </b>" . $row->is_manual;
                })
                ->addColumn("transaction_id", function ($row) {
                    $payment_gateway = "";
                    if (isset(config("web.gateway.payment_gateway")[$row->gateway_type])) {
                        $payment_gateway = config("web.gateway.payment_gateway")[$row->gateway_type];
                    }
                    return "<b>" . ucfirst($payment_gateway) . "</b><br><b>Tz Id : </b>" . $row->transaction_id . "<br> <b> Amount : </b>" . $row->tz_amount . "<br><b>Type : </b>" . ucfirst($row->loan_type_label);
                })
                ->addColumn("u_name", function ($row) {
                    return "<b>" . ucfirst($row->u_name) . "</b><br> <i class='tf-icons ti ti-phone'></i> " . $row->u_phone . "</b><br> <i class='tf-icons ti ti-mail'></i>" . $row->u_email . "<br> <i class='tf-icons ti ti-id'></i> " . $row->sub_card_number;
                })
                ->addColumn("action", function ($row) {
                    $html = "";
                    if ("0" == $row->status) {
                        $html = '<span class="badge bg-label-warning"><i class="ti ti-progress-alert"></i> pending </span>';
                    } else if ("1" == $row->status) {
                        $html = '<span class="badge bg-label-success"><i class="ti ti-progress-check"></i> success </span>';
                    } else if ("2" == $row->status) {
                        $html = '<span class="badge bg-label-danger"><i class="ti ti-square-rounded-check"></i> failed </span>';
                    } else if ("3" == $row->status) {
                        $html = '<span class="badge bg-label-primary"><i class="ti ti-square-progress-check"></i> refund </span>';
                    } else if ("4" == $row->status) {
                        $html = '<span class="badge bg-label-danger"><i class="ti ti-square-progress-check"></i> suspect </span>';
                    } else if ("5" == $row->status) {
                        $html = '<span class="badge bg-label-info"><i class="ti ti-square-rounded-check"></i> deactive </span>';
                    }
                    $html = $row->description . "<br>" . $html;

                    $html .= $this->getTableActionHtml([
                        "user" => route("_customersEdit", [
                            "key" => $row->u_uuid
                        ])
                    ]);
                    if (!empty($row->app_uuid)) {
                        $html .= $this->getTableActionHtml([
                            "application" => route("_applicationView", [
                                "key" => $row->app_uuid
                            ])
                        ]);
                    }
                    return $html;
                })
                ->rawColumns(["action", "rec_date", "transaction_id", "u_name"])
                ->make(true);
        }
        return view("backend.transactionsPostIndex");
    }

    public function subscriptionsPostIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = config("web.webapp.filter_from_date");
            $tdate = date("Y-m-d 00:00:00");
            if ($request->has("fdate") && !empty($request->fdate)) {
                $fdate = $request->fdate;
            }
            if ($request->has("tdate") && !empty($request->tdate)) {
                $tdate = $request->tdate . " 23:59:59";
            }

            $table_data = DB::table("user_subscriptions")->select([
                "user_subscriptions.id as p_id",
                "user_subscriptions.uuid",
                "user_subscriptions.rec_date",
                "user_subscriptions.start_date",
                "user_subscriptions.expiry_date",
                "user_subscriptions.card_number",
                "user_subscriptions.status",
                DB::raw("IF(user_subscriptions.is_manual = '1','Yes','No') as is_manual"),
                DB::raw("ROUND(IFNULL(user_subscriptions.amount,0), 2) as tz_amount"),
                DB::raw("DATE_FORMAT(user_subscriptions.start_date, '%Y-%m-%d') as f_start_date"),
                DB::raw("DATE_FORMAT(user_subscriptions.expiry_date, '%Y-%m-%d') as f_expiry_date"),
                "loan_applications.uuid as app_uuid",
                "loan_types.label as loan_type_label",
                "users.uuid as u_uuid",
                "users.name as u_name",
                "users.phone as u_phone",
                "users.email as u_email",
                "transactions.transaction_id as transaction_id",
                "transactions.uuid as tz_uuid",
            ])->leftJoin("users", function ($join) {
                $join->on("users.id", "=", "user_subscriptions.user_id");
            })->leftJoin("loan_applications", function ($join) {
                $join->on("loan_applications.id", "=", "user_subscriptions.application_id");
            })->leftJoin("loan_types", function ($join) {
                $join->on("loan_types.id", "=", "loan_applications.type_id");
            })->leftJoin("transactions", function ($join) {
                $join->on("transactions.object_id", "=", "user_subscriptions.id")
                    ->where("transactions.object_type", "=", "0");
            })
                ->where("user_subscriptions.deleted", "0")
                ->whereBetween("user_subscriptions.rec_date", [$fdate, $tdate])
                ->orderBy("user_subscriptions.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("rec_date", function ($row) {
                    $html = "";
                    if ("0" == $row->status) {
                        $html = '<span class="badge bg-label-warning"><i class="ti ti-progress-alert"></i> pending </span>';
                    } else if ("1" == $row->status) {
                        $html = '<span class="badge bg-label-success"><i class="ti ti-progress-check"></i> active </span>';
                    } else if ("2" == $row->status) {
                        $html = '<span class="badge bg-label-danger"><i class="ti ti-square-rounded-check"></i> expired </span>';
                    } else if ("3" == $row->status) {
                        $html = '<span class="badge bg-label-primary"><i class="ti ti-square-progress-check"></i> refund </span>';
                    } else if ("4" == $row->status) {
                        $html = '<span class="badge bg-label-danger"><i class="ti ti-square-progress-check"></i> deactive </span>';
                    }
                    return $row->rec_date . "<br> " . $html . "<br><b> Is Manual : </b>" . $row->is_manual;
                })
                ->addColumn("transaction_id", function ($row) {
                    return "<b>Amount : </b>" . $row->tz_amount . "<br><b>Type : </b>" . ucfirst($row->loan_type_label) . "<br> <b> Tz Id : </b>" . $row->transaction_id;
                })
                ->addColumn("u_name", function ($row) {
                    return "<b>" . ucfirst($row->u_name) . "</b><br> <i class='tf-icons ti ti-phone'></i> " . $row->u_phone . "</b><br> <i class='tf-icons ti ti-mail'></i>" . $row->u_email;
                })
                ->addColumn("card_number", function ($row) {
                    return "<b>Start : </b>" . $row->f_start_date . "</b><br> <b>End : </b>" . $row->f_expiry_date . "<br> <i class='tf-icons ti ti-id'></i> " . $row->card_number;
                })
                ->addColumn("action", function ($row) {
                    $html = $this->getTableActionHtml([
                        "user" => route("_customersEdit", [
                            "key" => $row->u_uuid
                        ])
                    ]);
                    if (!empty($row->app_uuid)) {
                        $html .= $this->getTableActionHtml([
                            "application" => route("_applicationView", [
                                "key" => $row->app_uuid
                            ])
                        ]);
                    }
                    return $html;
                })
                ->rawColumns(["action", "rec_date", "transaction_id", "u_name", "card_number"])
                ->make(true);
        }
        return view("backend.subscriptionsPostIndex");
    }
}
