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
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
            $query = DB::table("payment_transactions")->select([
                "payment_transactions.id as p_id",
                "payment_transactions.created_at",
                "payment_transactions.payment_gateway",
                "payment_transactions.gateway_payment_id",
                "payment_transactions.gateway_transaction_id",
                "payment_transactions.base_amount",
                "payment_transactions.gst_percentage",
                "payment_transactions.gst_amount",
                "payment_transactions.total_amount",
                "payment_transactions.status",
                "loan_applications.application_no as app_uuid",
                "loan_applications.login_type",
                "loan_types.label as loan_type_label",
                "users.uuid as u_uuid",
                "users.name as u_name",
                "users.phone as u_phone",
                "users.email as u_email",
            ])->leftJoin("users", function ($join) {
                $join->on("users.id", "=", "payment_transactions.user_id");
            })->leftJoin("loan_applications", function ($join) {
                $join->on("loan_applications.id", "=", "payment_transactions.loan_application_id");
            })->leftJoin("loan_types", function ($join) {
                $join->on("loan_types.id", "=", "loan_applications.loan_type_id");
            })
                ->when($fdate, fn ($q) => $q->where("payment_transactions.created_at", ">=", $fdate))
                ->when($tdate, fn ($q) => $q->where("payment_transactions.created_at", "<=", $tdate));

            if ($request->filled("status") && in_array($request->status, ["pending", "success", "failed", "refunded"], true)) {
                $query->where("payment_transactions.status", $request->status);
            }

            if ($request->filled("login_type") && in_array($request->login_type, ["self", "consultant"], true)) {
                $query->where("loan_applications.login_type", $request->login_type);
            }

            $summary = (clone $query)->reorder()
                ->selectRaw("COUNT(*) as c, ROUND(IFNULL(SUM(payment_transactions.total_amount),0),2) as t")
                ->first();

            $success = (clone $query)->reorder()->where("payment_transactions.status", "success")
                ->selectRaw("COUNT(*) as c, ROUND(IFNULL(SUM(payment_transactions.total_amount),0),2) as t, ROUND(IFNULL(SUM(payment_transactions.gst_amount),0),2) as g")
                ->first();

            $table_data = $query->orderBy("payment_transactions.id", "desc")->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("rec_date", function ($row) {
                    return date("d M Y", strtotime($row->created_at));
                })
                ->addColumn("rec_time", function ($row) {
                    return date("h:i A", strtotime($row->created_at));
                })
                ->addColumn("gateway", function ($row) {
                    return $row->payment_gateway ? ucfirst($row->payment_gateway) : "-";
                })
                ->addColumn("payment_id", function ($row) {
                    return $row->gateway_payment_id ?: "-";
                })
                ->addColumn("order_id", function ($row) {
                    return $row->gateway_transaction_id ?: "-";
                })
                ->addColumn("total_amt", function ($row) {
                    return "<b>&#8377; " . number_format((float) $row->total_amount, 2) . "</b>";
                })
                ->addColumn("u_name", function ($row) {
                    return $row->u_name ? ucfirst($row->u_name) : "-";
                })
                ->addColumn("u_mobile", function ($row) {
                    return $row->u_phone ?: "-";
                })
                ->addColumn("u_mail", function ($row) {
                    return $row->u_email ?: "-";
                })
                ->addColumn("app_no", function ($row) {
                    return $row->app_uuid ?: "-";
                })
                ->addColumn("loan_type", function ($row) {
                    return $row->loan_type_label ?: "-";
                })
                ->addColumn("login_type", function ($row) {
                    if ("consultant" === $row->login_type) {
                        return '<span class="badge bg-label-secondary">Hire Agent</span>';
                    }
                    if ("self" === $row->login_type) {
                        return '<span class="badge bg-label-secondary">Self Login</span>';
                    }
                    return "-";
                })
                ->addColumn("status", function ($row) {
                    $map = [
                        "pending" => ["warning", "ti-progress-alert"],
                        "success" => ["success", "ti-progress-check"],
                        "failed" => ["danger", "ti-square-rounded-x"],
                        "refunded" => ["info", "ti-arrow-back-up"],
                    ];
                    $badge = $map[$row->status] ?? ["secondary", "ti-help"];
                    return '<span class="badge bg-label-' . $badge[0] . '"><i class="ti ' . $badge[1] . '"></i> ' . ucfirst($row->status) . '</span>';
                })
                ->addColumn("action", function ($row) {
                    $url = [];
                    if (!empty($row->u_uuid)) {
                        $url["user"] = route("_customersEdit", ["key" => $row->u_uuid]);
                    }
                    if (!empty($row->app_uuid)) {
                        $url["application"] = route("_applicationView", ["key" => $row->app_uuid]);
                    }
                    return $this->getTableActionHtml($url);
                })
                ->with([
                    "summary" => [
                        "count" => (int) ($summary->c ?? 0),
                        "amount" => number_format((float) ($summary->t ?? 0), 2),
                        "success_count" => (int) ($success->c ?? 0),
                        "success_amount" => number_format((float) ($success->t ?? 0), 2),
                        "success_gst" => number_format((float) ($success->g ?? 0), 2),
                    ],
                ])
                ->rawColumns(["total_amt", "login_type", "status", "action"])
                ->make(true);
        }

        return view("backend.transactionsPostIndex");
    }

    public function subscriptionsPostIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
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
