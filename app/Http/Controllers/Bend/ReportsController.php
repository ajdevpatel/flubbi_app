<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{
    public function gstReportIndex(Request $request)
    {
        if ($request->ajax()) {
            $fdate = config("web.webapp.filter_from_date");
            $tdate = config("web.webapp.filter_to_date");
            if ($request->has("fdate") && !empty($request->fdate)) {
                $fdate = $request->fdate;
            }
            if ($request->has("tdate") && !empty($request->tdate)) {
                $tdate = $request->tdate . " 23:59:59";
            }

            $table_data = DB::table("user_subscriptions")
                ->select([
                    "user_subscriptions.id as p_id",
                    "user_subscriptions.uuid",
                    "user_subscriptions.rec_date as inv_date",
                    "user_invoices.cgst",
                    "user_invoices.sgst",
                    "user_invoices.igst",
                    DB::raw("ROUND(IFNULL(user_invoices.price,0), 2) as price"),
                    DB::raw("ROUND(IFNULL(user_invoices.grandtotal,0), 2) as grandtotal"),
                    "users.gstno as u_gstno",
                    "users.pincode as u_pincode",
                    "users.city as u_city",
                    "users.phone as u_phone",
                    "users.email as u_email",
                    "users.name as u_name",
                    "states.name as states_label",
                    DB::raw("CONCAT(loan_types.inv_prefix,user_invoices.inv_number) as inv_number"),
                ])->leftJoin("user_invoices", function ($join) {
                    $join->on("user_invoices.object_id", "=", "user_subscriptions.id");
                })->leftJoin("loan_applications", function ($join) {
                    $join->on("loan_applications.id", "=", "user_subscriptions.application_id");
                })->leftJoin("loan_types", function ($join) {
                    $join->on("loan_types.id", "=", "loan_applications.type_id");
                })->leftJoin("users", function ($join) {
                    $join->on("users.id", "=", "user_subscriptions.user_id");
                })->leftJoin("states", function ($join) {
                    $join->on("states.id", "=", "users.state_id");
                })
                ->where("user_subscriptions.status", "1")
                ->where("user_subscriptions.deleted", "0")
                ->whereBetween("user_subscriptions.rec_date", [$fdate, $tdate])
                ->orderBy("user_subscriptions.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("inv_date", function ($row) {
                    return $row->inv_date . "<br><b> INV No. :</b>" . $row->inv_number;
                })
                ->addColumn("cgst", function ($row) {
                    return "<b> CGST : </b>" . $row->cgst . "<br><b>SGST : </b>" . $row->sgst . "<br><b>IGST : </b>" . $row->igst;
                })
                ->addColumn("grandtotal", function ($row) {
                    return "<b> GST No. : </b>" . $row->u_gstno . "<br><b>Price : </b>" . $row->price . "<br><b>Grand Amount : </b>" . $row->grandtotal;
                })
                ->addColumn("u_city", function ($row) {
                    return "<b> Pin : </b>" . $row->u_pincode . "<br><b>City : </b>" . $row->u_city . "<br><b>State : </b>" . $row->states_label;
                })
                ->addColumn("u_name", function ($row) {
                    return "<b>" . ucfirst($row->u_name) . "</b><br><b>Phone : </b>" . $row->u_phone . "<br><b>Mail : </b>" . $row->u_email;
                })
                ->rawColumns(["inv_date", "cgst", "grandtotal", "u_name", "u_city"])
                ->make(true);
        }
        return view("backend.reports.gstReportIndex");
    }
}
