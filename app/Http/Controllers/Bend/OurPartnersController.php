<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class OurPartnersController extends Controller
{

    public function roiPackagesIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $table_data = DB::table("roi_packages")->select([
                "roi_packages.id as p_id",
                "roi_packages.uuid",
                "roi_packages.min_roi",
                "roi_packages.max_roi",
                "roi_packages.roi",
                "roi_packages.processing_fee",
                "roi_packages.terms_years",
                "roi_packages.terms_months",
                "roi_packages.status",
                "roi_packages.label",
                "loan_types.label as loan_type_label",
                "our_partners.label as our_partner_label",
                DB::raw("CONCAT('" . config("web.webapp.base_url") . "','store/partners/',our_partners.logo) as partner_logo"),
            ])->leftJoin("loan_types", function ($join) {
                $join->on("loan_types.id", "=", "roi_packages.type_id");
            })->leftJoin("our_partners", function ($join) {
                $join->on("our_partners.id", "=", "roi_packages.partner_id");
            })->where([
                "roi_packages.deleted" => "0",
            ])->orderBy("roi_packages.id", "desc")->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("partner_logo", function ($row) {
                    return '<img src="' . $row->partner_logo . '" width="100" class="img-thumbnail">';
                })
                ->addColumn("our_partner_label", function ($row) {
                    return '<b><u>' . $row->our_partner_label . '</u></b><br> Type : ' . $row->loan_type_label;
                })
                ->addColumn("roi", function ($row) {
                    return '<b>' . $row->roi . '</b><br> Min : ' . $row->min_roi . '<br> Max : ' . $row->max_roi;
                })
                ->addColumn("terms_years", function ($row) {
                    return '<b>Years :</b>' . $row->terms_years . '<br> <b>Months : </b> ' . $row->terms_months;
                })
                ->addColumn("status", function ($row) {
                    $html = '<span class="badge bg-label-success"><i class="ti ti-square-check"></i> Active </span>';
                    if ("1" == $row->status) {
                        $html = '<span class="badge bg-label-danger"><i class="ti ti-square-x"></i> De-Active </span>';
                    }
                    return $html;
                })
                ->addColumn("action", function ($row) {
                    return $this->getTableActionHtml([
                        "edit" => "#"
                    ]);
                })
                ->rawColumns(["partner_logo", "our_partner_label", "terms_years", "roi", "status", "action"])
                ->make(true);
        }

        $loan_type_index = collect(DB::table("loan_types")->select([
            "loan_types.id",
            "loan_types.label",
        ])->where("loan_types.status", "0")
            ->where("loan_types.deleted", "0")
            ->orderBy("loan_types.priority", "asc")
            ->get())->toArray();

        $our_partners_index = collect(DB::table("our_partners")->select([
            "our_partners.id",
            "our_partners.label",
        ])->where("our_partners.status", "1")
            ->where("our_partners.deleted", "0")
            ->orderBy("our_partners.priority", "asc")
            ->get())->toArray();

        return view("backend.roiPackagesIndex", [
            "loan_type_index" => $loan_type_index,
            "our_partners_index" => $our_partners_index,
        ]);
    }

    public function roiPackagesStore(Request $request)
    {
        if ($request->ajax() && "PUT" === $request->method()) {
            Validator::make($request->all(), [
                "partner" => "required|numeric|exists:our_partners,id",
                "type" => "required|numeric|exists:loan_types,id",
                "min" => "required|numeric|min:6|max:30",
                "max" => "required|numeric|min:6|max:30",
                "roi" => "required|numeric|min:6|max:30",
                "t_years" => "required|numeric|min:1|max:30",
                "t_months" => "required|numeric|min:12|max:360",
                "fee" => "required|numeric",
                "label" => "required",
            ])->validate();

            try {
                DB::beginTransaction();

                $in_data = [
                    "min_roi" => $request->min,
                    "max_roi" => $request->max,
                    "roi" => $request->roi,
                    "processing_fee" => $request->fee,
                    "terms_years" => $request->t_years,
                    "terms_months" => $request->t_months,
                    "label" => $request->label,
                    "description" => $request->description,
                ];

                $action_msg = "Your data has been successfully created.";
                $roi_data = collect(DB::table("roi_packages")->where("type_id", $request->type)->where("partner_id", $request->partner)->first())->toArray();
                if ([] == $roi_data) {
                    $in_data["uuid"] = $this->generateUUID();
                    $in_data["partner_id"] = $request->partner;
                    $in_data["type_id"] = $request->type;
                    $in_data["created_by"] = $this->getLoginUserId();
                    $in_data["created_at"] = $this->currentDataTime();
                    DB::table("roi_packages")->insertGetId($in_data);
                } else {
                    $in_data["updated_by"] = $this->getLoginUserId();
                    $in_data["updated_at"] = $this->currentDataTime();
                    DB::table("roi_packages")->where("type_id", $request->type)->where("partner_id", $request->partner)->update($in_data);
                    $action_msg = "Your data has been successfully updated.";
                }

                DB::commit();
                return response()->json(["message" => $action_msg], 200);
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
}
