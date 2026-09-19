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

class SupportController extends Controller
{

    public function supportPostIndex(Request $request)
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

            $table_data = DB::table("support_tickets")->select([
                "support_tickets.id as p_id",
                "support_tickets.ticket_no",
                "support_tickets.message",
                "users.name",
                "users.email",
                "users.phone",
                "support_tickets.status",
                "support_tickets.created_at",
                "support_reasons.label as reason_label",
                "users.uuid as u_uuid",
            ])->leftJoin("users", function ($join) {
                $join->on("users.id", "=", "support_tickets.user_id");
            })->leftJoin("support_reasons", function ($join) {
                $join->on("support_reasons.id", "=", "support_tickets.reason_id");
            })
                ->whereBetween("support_tickets.created_at", [$fdate, $tdate])
                ->orderBy("support_tickets.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("full_name", function ($row) {
                    return $row->name;
                })
                ->addColumn("mobile", function ($row) {
                    return $row->phone;
                })
                ->addColumn("email", function ($row) {
                    return $row->email;
                })
                ->addColumn("created_at", function ($row) {
                    return $row->created_at;
                })
                ->addColumn("ticket_no", function ($row) {
                    return "<b>" . $row->ticket_no . "</b>";
                })
                ->addColumn("message", function ($row) {
                    return $row->message;
                })
                ->addColumn("reason_label", function ($row) {
                    return $row->reason_label;
                })
                ->addColumn("status", function ($row) {
                    $html = "";
                    if ("open" == $row->status) {
                        $html = '<span class="badge bg-label-warning"><i class="ti ti-progress-alert"></i> open </span>';
                    } else if ("processing" == $row->status) {
                        $html = '<span class="badge bg-label-info"><i class="ti ti-progress-check"></i> processing </span>';
                    } else if ("close/no response" == $row->status) {
                        $html = '<span class="badge bg-label-danger"><i class="ti ti-square-rounded-check"></i> close/no response </span>';
                    } else if ("hold" == $row->status) {
                        $html = '<span class="badge bg-label-primary"><i class="ti ti-square-progress-check"></i> hold </span>';
                    } else if ("reopen" == $row->status) {
                        $html = '<span class="badge bg-label-info"><i class="ti ti-square-progress-check"></i> reopen </span>';
                    } else if ("solve" == $row->status) {
                        $html = '<span class="badge bg-label-success"><i class="ti ti-square-rounded-check"></i> solve </span>';
                    }
                    return $html;
                })
                ->addColumn("action", function ($row) {
                    $html = ""; /* $this->getTableActionHtml([
                        "edit" => route("_supportPostEdit", [
                            "key" => $row->p_id
                        ])
                    ]);
                    if (!empty($row->u_uuid)) {
                        $html .= $this->getTableActionHtml([
                            "user" => route("_customersEdit", [
                                "key" => $row->u_uuid
                            ])
                        ]);
                    } */
                    return $html;
                })
                ->rawColumns(["action", "status", "ticket_no", "reason_label", "message", "created_at", "full_name", "mobile", "email"])
                ->make(true);
        }
        return view("backend.supportPostIndex");
    }

    public function supportPostEdit(string $key = null, Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            Validator::make($request->all(), [
                "id" => "required|exists:support_tickets,id",
                "status" => "required|in:open,processing,close/no response,hold,reopen,solve",
            ])->validate();

            try {
                DB::beginTransaction();
                DB::table("support_tickets")
                    ->where("support_tickets.id", $request->id)
                    ->update([
                        "status" => $request->status,
                        "updated_by" => $this->getLoginUserId(),
                        "updated_at" => $this->currentDataTime(),
                    ]);

                DB::commit();
                return response()->json(["message" => "You have successfully added your data."], 200);
            } catch (\Exception $e) {
                return response()->json([
                    "errors" => [
                        "message" => ["The request was unsuccessful. - " . $e->getMessage()]
                    ]
                ], 422);
            }
        }

        $table_data = DB::table("support_tickets")->select([
            "support_tickets.id as p_id",
            "support_tickets.ticket_no",
            "support_tickets.message",
            "users.name",
            "users.email",
            "users.phone",
            "support_tickets.status",
            "support_tickets.created_at",
            "support_tickets.updated_at",
            "support_tickets.message",
            "support_reasons.label as reason_label",
            "users.uuid as u_uuid",
        ])->leftJoin("users", function ($join) {
            $join->on("users.id", "=", "support_tickets.user_id");
        })->leftJoin("support_reasons", function ($join) {
            $join->on("support_reasons.id", "=", "support_tickets.reason_id");
        })
            ->where("support_tickets.id", $key)
            ->first();

        if (null != $table_data) {
            return view("backend.supportPostEdit", [
                "data" => $table_data,
            ]);
        }
        return redirect()->route("_supportPostIndex")->withErrors("please check edit records and try again.");
    }
}
