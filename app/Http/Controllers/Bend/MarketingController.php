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

class MarketingController extends Controller
{

    public function manualMarketingIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
            $table_data = DB::table("marketing_manual_index")->select([
                "marketing_manual_index.id as p_id",
                "marketing_manual_index.*",
                DB::raw("IF(marketing_manual_index.is_phone = '1','Yes','No') as is_phone"),
                DB::raw("IF(marketing_manual_index.is_wapp = '1','Yes','No') as is_wapp"),
                DB::raw("IF(marketing_manual_index.is_mail = '1','Yes','No') as is_mail"),
            ])
                ->where("marketing_manual_index.deleted", "0")
                ->whereBetween("marketing_manual_index.created_at", [$fdate, $tdate])
                ->orderBy("marketing_manual_index.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("rec_date", function ($row) {
                    return "<b>Rec. : </b>" . $row->rec_date . "<br> <b> Created : </b>" . $row->created_at;
                })
                ->addColumn("is_phone", function ($row) {
                    return "<i class='tf-icons ti ti-phone'></i> - <small>" . $row->is_phone . "</small><br> <i class='tf-icons ti ti-brand-whatsapp'></i> - <small>" . $row->is_wapp . "</small>";
                })
                ->addColumn("status", function ($row) {
                    $html = "<b>Total : </b>" . $row->total_data . "<br>";
                    if ("0" == $row->status) {
                        $html .= '<span class="badge bg-label-warning"><i class="ti ti-progress-alert"></i> pending </span>';
                    } else if ("1" == $row->status) {
                        $html .= '<span class="badge bg-label-success"><i class="ti ti-progress-check"></i> success </span>';
                    } else if ("2" == $row->status) {
                        $html .= '<span class="badge bg-label-danger"><i class="ti ti-square-rounded-check"></i> failed </span>';
                    } else if ("3" == $row->status) {
                        $html .= '<span class="badge bg-label-primary"><i class="ti ti-square-progress-check"></i> hold </span>';
                    } else if ("4" == $row->status) {
                        $html .= '<span class="badge bg-label-danger"><i class="ti ti-square-progress-check"></i> deactive </span>';
                    }
                    return $html;
                })
                ->addColumn("message_text", function ($row) {
                    return "<textarea disabled class='form-control' rows='2'>" . $row->message_text . "</textarea>";
                })
                ->addColumn("action", function ($row) {
                    return $this->getTableActionHtml([
                        "view" => "#"
                    ]);
                })
                ->rawColumns(["action", "rec_date", "is_phone", "status", "message_text"])
                ->make(true);
        }
        return view("backend.manualMarketingIndex");
    }

    public function addManualMarketingPost(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $validator = Validator::make($request->all(), [
                "import_file" => "required|file|mimes:csv,txt|max:10000",
                "r_date" => "required|date|after_or_equal:today",
                "phone" => "required:in:0,1",
                "whatsapp" => "required:in:0,1",
            ], [
                "r_date.*" => "The record date field must be a date after or equal to today"
            ])->validate();

            try {
                DB::beginTransaction();

                $csvfile = fopen($request->file("import_file"), "r");
                fgetcsv($csvfile);

                $regdatetime = date('Y-m-d', strtotime($request->r_date)) . " " . date('H:i:s');
                $marketing_manual_index_id = DB::table("marketing_manual_index")->insertGetId([
                    "uuid" => $this->generateUUID(),
                    "rec_date" => $regdatetime,
                    "is_phone" => $request->phone,
                    "is_wapp" => $request->whatsapp,
                    "message_text" => $request->loan_amount,
                    "note" => $request->cibil_scores,
                    "created_by" => $this->getLoginUserId(),
                    "created_at" => $this->currentDataTime()
                ]);

                $in_arr = [];
                while (($row = fgetcsv($csvfile, 10000, ",")) !== FALSE) {
                    $in_arr[$row["1"]] = [
                        "marketing_manual_index_id" => $marketing_manual_index_id,
                        "name" => $row["0"],
                        "phone" => $row["1"],
                        "mail" => $row["2"],
                    ];
                }
                if ([] == $in_arr) {
                    return response()->json([
                        "errors" => [
                            "message" => ["The file is empty please select the valid file and data."]
                        ]
                    ], 422);
                }

                $insert_bulk_data = DB::table("marketing_manual_data")->insert($in_arr);

                $update_total_data = DB::table("marketing_manual_index")->where("marketing_manual_index.id", $marketing_manual_index_id)->update([
                    "total_data" => collect($in_arr)->count()
                ]);

                DB::commit();
                return response()->json(["message" => "CSV file imported successfully."], 200);
            } catch (\Exception $e) {
                return response()->json([
                    "errors" => [
                        "message" => ["The request was unsuccessful. - " . $e->getMessage()]
                    ]
                ], 422);
            }
        }
        return response()->json([
            "errors" => [
                "message" => ["The request was unsuccessful."]
            ]
        ], 422);
    }




    public function otpLogsIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
            $table_data = DB::table("otp_logs")->select([
                "otp_logs.id as p_id",
                "otp_logs.phone",
                "otp_logs.otp",
                "otp_logs.expires_at",
                "otp_logs.created_at",
                DB::raw("IF(otp_logs.is_used = '1','Yes','No') as status"),
            ])->when($fdate, fn ($q) => $q->where("otp_logs.created_at", ">=", $fdate))->when($tdate, fn ($q) => $q->where("otp_logs.created_at", "<=", $tdate))
                ->orderBy("otp_logs.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("phone", function ($row) {
                    return !empty($row->phone) ? $row->phone : "-";
                })
                ->addColumn("otp", function ($row) {
                    return !empty($row->otp) ? $row->otp : "-";
                })
                ->addColumn("date", function ($row) {
                    return !empty($row->created_at) ? date("d M Y", strtotime($row->created_at)) : "-";
                })
                ->addColumn("time", function ($row) {
                    return !empty($row->created_at) ? date("h:i A", strtotime($row->created_at)) : "-";
                })
                ->rawColumns([])
                ->make(true);
        }
        return view("backend.otpLogsIndex");
    }
}
