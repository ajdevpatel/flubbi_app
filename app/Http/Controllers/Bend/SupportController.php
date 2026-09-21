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
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
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
                ->when($fdate, fn ($q) => $q->where("support_tickets.created_at", ">=", $fdate))
                ->when($tdate, fn ($q) => $q->where("support_tickets.created_at", "<=", $tdate))
                ->orderBy("support_tickets.id", "desc")
                ->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("created_date", function ($row) {
                    if (empty($row->created_at)) {
                        return "-";
                    }
                    return date("d M Y", strtotime($row->created_at));
                })
                ->addColumn("created_time", function ($row) {
                    if (empty($row->created_at)) {
                        return "-";
                    }
                    return date("h:i A", strtotime($row->created_at));
                })
                ->addColumn("full_name", function ($row) {
                    return !empty($row->name) ? e($row->name) : "-";
                })
                ->addColumn("mobile", function ($row) {
                    return !empty($row->phone) ? e($row->phone) : "-";
                })
                ->addColumn("email", function ($row) {
                    return !empty($row->email) ? e($row->email) : "-";
                })
                ->addColumn("ticket_no", function ($row) {
                    if (empty($row->ticket_no)) {
                        return "-";
                    }
                    return "<b>" . e($row->ticket_no) . "</b>";
                })
                ->addColumn("message", function ($row) {
                    return !empty($row->message) ? e($row->message) : "-";
                })
                ->addColumn("reason_label", function ($row) {
                    return !empty($row->reason_label) ? e($row->reason_label) : "-";
                })
                ->addColumn("status", function ($row) {
                    $badges = [
                        "open" => ["bg-label-warning", "ti-progress-alert", "Open"],
                        "in_progress" => ["bg-label-info", "ti-progress-check", "In Progress"],
                        "processing" => ["bg-label-info", "ti-progress-check", "Processing"],
                        "hold" => ["bg-label-primary", "ti-player-pause", "Hold"],
                        "reopen" => ["bg-label-info", "ti-refresh", "Re-open"],
                        "resolved" => ["bg-label-success", "ti-square-rounded-check", "Resolved"],
                        "solve" => ["bg-label-success", "ti-square-rounded-check", "Solve"],
                        "closed" => ["bg-label-secondary", "ti-square-rounded-x", "Closed"],
                        "close/no response" => ["bg-label-danger", "ti-square-rounded-x", "Close/No Response"],
                    ];
                    if (empty($row->status)) {
                        return "-";
                    }
                    if (!array_key_exists($row->status, $badges)) {
                        return '<span class="badge bg-label-secondary">' . e($row->status) . '</span>';
                    }
                    [$class, $icon, $label] = $badges[$row->status];
                    return '<span class="badge ' . $class . '"><i class="ti ' . $icon . '"></i> ' . $label . ' </span>';
                })
                ->addColumn("action", function ($row) {
                    $html = $this->getTableActionHtml([
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
                    }
                    return $html;
                })
                ->rawColumns(["action", "status", "ticket_no"])
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
