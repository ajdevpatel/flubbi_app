<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function invoicePostIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;

            $query = $this->baseQuery()
                ->when($fdate, fn ($q) => $q->where("payment_transactions.created_at", ">=", $fdate))
                ->when($tdate, fn ($q) => $q->where("payment_transactions.created_at", "<=", $tdate));

            if ($request->filled("login_type") && in_array($request->login_type, ["self", "consultant"], true)) {
                $query->where("loan_applications.login_type", $request->login_type);
            }

            $service = new InvoicePdfService();
            $table_data = $query->orderBy("payment_transactions.id", "desc")->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("inv_no", function ($row) use ($service) {
                    return $service->invoiceNumber((int) $row->p_id);
                })
                ->addColumn("rec_date", function ($row) {
                    return date("d M Y", strtotime((string) $row->created_at));
                })
                ->addColumn("payment_id", function ($row) {
                    return $row->gateway_payment_id ?: "-";
                })
                ->addColumn("order_id", function ($row) {
                    return $row->gateway_transaction_id ?: "-";
                })
                ->addColumn("base_amt", function ($row) {
                    return "&#8377; " . number_format((float) $row->base_amount, 2);
                })
                ->addColumn("gst_rate", function ($row) {
                    return rtrim(rtrim(number_format((float) $row->gst_percentage, 2, ".", ""), "0"), ".") . "%";
                })
                ->addColumn("gst_amt", function ($row) {
                    return "&#8377; " . number_format((float) $row->gst_amount, 2);
                })
                ->addColumn("total_amt", function ($row) {
                    return "<b>&#8377; " . number_format((float) $row->total_amount, 2) . "</b>";
                })
                ->addColumn("u_name", function ($row) {
                    return $row->u_name ? ucwords((string) $row->u_name) : "-";
                })
                ->addColumn("u_mail", function ($row) {
                    return $row->u_email ?: "-";
                })
                ->addColumn("u_mobile", function ($row) {
                    return $row->u_phone ?: "-";
                })
                ->addColumn("app_no", function ($row) {
                    return $row->app_uuid ?: "-";
                })
                ->addColumn("action", function ($row) {
                    $url = ["invoice" => route("_invoiceDownload", ["key" => $row->p_id])];
                    if (!empty($row->app_uuid)) {
                        $url["application"] = route("_applicationView", ["key" => $row->app_uuid]);
                    }
                    if (!empty($row->u_uuid)) {
                        $url["user"] = route("_customersEdit", ["key" => $row->u_uuid]);
                    }

                    return $this->getTableActionHtml($url);
                })
                ->rawColumns(["base_amt", "gst_amt", "total_amt", "action"])
                ->make(true);
        }

        return view("backend.invoiceIndex");
    }

    public function invoiceDownload(string $key)
    {
        $row = $this->baseQuery()->where("payment_transactions.id", (int) $key)->first();

        if (null === $row) {
            abort(404);
        }

        return (new InvoicePdfService())->download($row);
    }

    private function baseQuery()
    {
        return (new InvoicePdfService())->query();
    }
}
