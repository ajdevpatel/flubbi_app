<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SelfLoginBanksController extends Controller
{
    public function selfLoginBanksIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $table_data = DB::table("self_login_banks")->select([
                "id as p_id",
                "label",
                "logo",
                "pl_link",
                "bl_link",
                "status",
                "sort_order",
            ])->orderBy("sort_order", "asc")->orderBy("label", "asc")->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("logo_img", function ($row) {
                    if (empty($row->logo)) {
                        return '<span class="text-muted">No logo</span>';
                    }
                    return '<img src="' . asset("banks/" . $row->logo) . '" width="55" class="img-thumbnail">';
                })
                ->addColumn("bank", function ($row) {
                    return "<b>" . e($row->label) . "</b><br><small class='text-muted'>Sort : " . (int) $row->sort_order . "</small>";
                })
                ->addColumn("links", function ($row) {
                    return "<small><b>PL :</b> " . e($row->pl_link) . "<br><b>BL :</b> " . e($row->bl_link) . "</small>";
                })
                ->addColumn("status_badge", function ($row) {
                    if (1 == (int) $row->status) {
                        return '<span class="badge bg-label-success"><i class="ti ti-square-check"></i> Active </span>';
                    }
                    return '<span class="badge bg-label-danger"><i class="ti ti-square-x"></i> De-Active </span>';
                })
                ->addColumn("action", function ($row) {
                    return '<button type="button" class="btn btn-sm btn-primary editBankBtn" data-id="' . (int) $row->p_id . '"><i class="ti ti-edit"></i> Edit</button>';
                })
                ->rawColumns(["logo_img", "bank", "links", "status_badge", "action"])
                ->make(true);
        }

        return view("backend.selfLoginBanksIndex");
    }

    public function selfLoginBanksStore(Request $request)
    {
        if ($request->ajax() && "PUT" === $request->method()) {
            Validator::make($request->all(), [
                "id" => "nullable|exists:self_login_banks,id",
                "label" => "required|string|max:255",
                "pl_link" => "required|string",
                "bl_link" => "required|string",
                "status" => "required|in:0,1",
                "sort_order" => "required|integer|min:0",
                "logo" => ($request->filled("id") ? "nullable" : "required") . "|image|mimes:png,jpg,jpeg,webp|max:2048",
            ])->validate();

            try {
                DB::beginTransaction();

                $data = [
                    "label" => $request->label,
                    "pl_link" => $request->pl_link,
                    "bl_link" => $request->bl_link,
                    "status" => (int) $request->status,
                    "sort_order" => (int) $request->sort_order,
                ];

                if ($request->hasFile("logo")) {
                    $file = $request->file("logo");
                    $filename = "bank_" . time() . "_" . mt_rand(1000, 9999) . "." . strtolower($file->getClientOriginalExtension());
                    $file->move(public_path("banks"), $filename);
                    $data["logo"] = $filename;
                }

                if ($request->filled("id")) {
                    DB::table("self_login_banks")->where("id", $request->id)->update($data);
                    $action_msg = "Bank has been successfully updated.";
                } else {
                    DB::table("self_login_banks")->insert($data);
                    $action_msg = "Bank has been successfully created.";
                }

                DB::commit();

                return response()->json(["message" => $action_msg], 200);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    "errors" => ["message" => ["The request was unsuccessful. - " . $e->getMessage()]]
                ], 422);
            }
        }

        return response()->json([
            "errors" => ["message" => ["The request was unsuccessful."]]
        ], 422);
    }

    public function bankApplicationsIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;

            $table_data = DB::table("loan_bank_applications as a")
                ->leftJoin("self_login_banks as b", "b.id", "=", "a.self_login_bank_id")
                ->leftJoin("loan_applications as la", "la.id", "=", "a.loan_application_id")
                ->leftJoin("loan_types as lt", "lt.id", "=", "la.loan_type_id")
                ->leftJoin("users as u", "u.id", "=", "a.user_id")
                ->select([
                    "a.id as p_id",
                    "a.apply_count",
                    "a.first_applied_at",
                    "a.last_applied_at",
                    "b.label as bank_label",
                    "b.logo as bank_logo",
                    "la.application_no as app_uuid",
                    "lt.label as loan_type",
                    "u.uuid as u_uuid",
                    "u.name as u_name",
                    "u.phone as u_phone",
                    "u.email as u_email",
                ])
                ->when($fdate, fn ($x) => $x->where("a.last_applied_at", ">=", $fdate))
                ->when($tdate, fn ($x) => $x->where("a.last_applied_at", "<=", $tdate))
                ->orderByDesc("a.last_applied_at")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("customer", function ($row) {
                    return "<b>" . e(ucwords((string) $row->u_name)) . "</b><br><small>" . e($row->u_phone) . "<br>" . e($row->u_email) . "</small>";
                })
                ->addColumn("application", function ($row) {
                    return "<b>" . e($row->app_uuid) . "</b><br><small class='text-muted'>" . e($row->loan_type) . "</small>";
                })
                ->addColumn("bank", function ($row) {
                    $img = $row->bank_logo ? '<img src="' . asset("banks/" . $row->bank_logo) . '" width="40" class="img-thumbnail me-1">' : "";
                    return $img . "<b>" . e(ucwords((string) $row->bank_label)) . "</b>";
                })
                ->addColumn("apply_count", function ($row) {
                    return '<span class="badge bg-label-primary">' . (int) $row->apply_count . ' time(s)</span>';
                })
                ->addColumn("applied", function ($row) {
                    $first = $row->first_applied_at ? date("d M Y, h:i A", strtotime($row->first_applied_at)) : "-";
                    $last = $row->last_applied_at ? date("d M Y, h:i A", strtotime($row->last_applied_at)) : "-";
                    return "<small><b>First :</b> " . $first . "<br><b>Last :</b> " . $last . "</small>";
                })
                ->addColumn("action", function ($row) {
                    $url = [];
                    if (!empty($row->app_uuid)) {
                        $url["application"] = route("_applicationView", ["key" => $row->app_uuid]);
                    }
                    if (!empty($row->u_uuid)) {
                        $url["user"] = route("_customersEdit", ["key" => $row->u_uuid]);
                    }
                    return $this->getTableActionHtml($url);
                })
                ->rawColumns(["customer", "application", "bank", "apply_count", "applied", "action"])
                ->make(true);
        }

        return view("backend.bankApplicationsIndex");
    }

    public function selfLoginBanksEdit(string $id = null, Request $request)
    {
        $bank = DB::table("self_login_banks")->where("id", $request->uuid)->first();

        if (!$bank) {
            return response()->json([
                "errors" => ["message" => ["Bank not found."]]
            ], 422);
        }

        $bank->logo_url = $bank->logo ? asset("banks/" . $bank->logo) : "";

        return response()->json(["data" => $bank], 200);
    }
}
