<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadsController extends Controller
{
    public function leadEnquiryIndex(Request $request)
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

            $table_data = DB::table("lead_enquiry")
                ->select([
                    "lead_enquiry.id as p_id",
                    "lead_enquiry.*",
                    "lead_type.label as lead_type",
                    "lead_amount.label as lead_amount",
                ])->leftJoin("lead_type", function ($join) {
                    $join->on("lead_type.id", "=", "lead_enquiry.type_id")
                        ->where("lead_type.deleted", "0");
                })->leftJoin("lead_amount", function ($join) {
                    $join->on("lead_amount.id", "=", "lead_enquiry.amount")
                        ->where("lead_amount.deleted", "0");
                })
                ->where("lead_enquiry.deleted", "0")
                ->whereBetween("lead_enquiry.created_at", [$fdate, $tdate])
                ->orderBy("lead_enquiry.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("status", function ($row) {
                    $html = "";
                    if ("0" == $row->status) {
                        $html = '<span class="badge bg-label-warning"><i class="ti ti-progress-alert"></i> Pending </span>';
                    } else if ("1" == $row->status) {
                        $html = '<span class="badge bg-label-info"><i class="ti ti-progress-check"></i> Progress </span>';
                    } else if ("2" == $row->status) {
                        $html = '<span class="badge bg-label-success"><i class="ti ti-square-rounded-check"></i> Done </span>';
                    }
                    return $html;
                })
                ->addColumn("phone", function ($row) {
                    return "<b>" . ucfirst($row->name) . "</b><br><b>Phone : </b>" . $row->phone;
                })
                ->addColumn("type", function ($row) {
                    $html = "";
                    if ("0" == $row->type) {
                        $html = '<span class="badge bg-label-success"><i class="ti ti-message-plus"></i> Chat - Lead </span>';
                    } else if ("1" == $row->type) {
                        $html = '<span class="badge bg-label-info"><i class="ti ti-progress-check"></i> Blog - Lead </span>';
                    }
                    return $html;
                })
                ->addColumn("action", function ($row) {
                    return $this->getTableActionHtml([
                        "edit" => route("_leadEnquiryEdit", [
                            "key" => $row->uuid
                        ])
                    ]);
                })
                ->rawColumns(["action", "status", "phone", "type"])
                ->make(true);
        }
        return view("backend.leadEnquiryIndex");
    }

    public function leadEnquiryEdit(string $id = null)
    {
        $table_data = DB::table("lead_enquiry")
            ->select([
                "lead_enquiry.id as p_id",
                "lead_enquiry.*",
                "lead_type.label as lead_type",
                "lead_amount.label as lead_amount",
            ])->leftJoin("lead_type", function ($join) {
                $join->on("lead_type.id", "=", "lead_enquiry.type_id")
                    ->where("lead_type.deleted", "0");
            })->leftJoin("lead_amount", function ($join) {
                $join->on("lead_amount.id", "=", "lead_enquiry.amount")
                    ->where("lead_amount.deleted", "0");
            })
            ->where("lead_enquiry.uuid", $id)
            ->where("lead_enquiry.deleted", "0")
            ->orderBy("lead_enquiry.id", "desc")
            ->first();

        dd($table_data);

        if (null != $table_data) {
            return view("backend.enquiryEdit", [
                "data" => $table_data,
            ]);
        }
        return redirect()->route("_leadEnquiryIndex")->withErrors("please check edit records and try again.");
    }

    /*
    public function postUpdate(string $key = "", Request $request)
    {
        Validator::make($request->all(), [
            "uuid" => "required|exists:enquiry,uuid",
            "id" => "required|numeric|exists:enquiry,id",
            "status" => "required|numeric|in:0,1,2",
        ])->validate();

        $table_data = DB::table("enquiry")
            ->where("enquiry.uuid", $request->uuid)
            ->where("enquiry.id", $request->id)
            ->update([
                "note" => $request->note,
                "status" => $request->status,
                "updated_at" => $this->currentDataTime(),
                "updated_by" => $this->getLoginUserId(),
            ]);

        return response()->json(["message" => "You have successfully updated your data."], 200);
    }
    */
}
