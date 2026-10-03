<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EnquiriesController extends Controller
{
    private const STATUS = [
        "0" => ["Pending", "warning", "ti-clock-hour-4"],
        "1" => ["In Progress", "info", "ti-clock-hour-4"],
        "2" => ["Done", "success", "ti-circle-check"],
    ];

    public function enquiriesPostIndex(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;

            $query = DB::table("web_enquiry")
                ->select(["id", "uuid", "name", "phone", "mail", "description", "status", "created_at"])
                ->where("deleted", "0")
                ->when($fdate, fn ($q) => $q->where("created_at", ">=", $fdate))
                ->when($tdate, fn ($q) => $q->where("created_at", "<=", $tdate));

            if ($request->filled("status") && array_key_exists((string) $request->status, self::STATUS)) {
                $query->where("status", (string) $request->status);
            }

            $table_data = $query->orderBy("id", "desc")->get();

            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("rec_date", function ($row) {
                    return date("d M Y", strtotime((string) $row->created_at));
                })
                ->addColumn("rec_time", function ($row) {
                    return date("h:i A", strtotime((string) $row->created_at));
                })
                ->addColumn("u_name", function ($row) {
                    return $row->name ? ucwords((string) $row->name) : "-";
                })
                ->addColumn("u_mobile", function ($row) {
                    return $row->phone ?: "-";
                })
                ->addColumn("u_mail", function ($row) {
                    return $row->mail ?: "-";
                })
                ->addColumn("message", function ($row) {
                    return $row->description ?: "-";
                })
                ->addColumn("status_badge", function ($row) {
                    $badge = self::STATUS[(string) $row->status] ?? ["Unknown", "secondary", "ti-help"];

                    return '<span class="badge bg-label-' . $badge[1] . '"><i class="ti ' . $badge[2] . '"></i> ' . $badge[0] . '</span>';
                })
                ->addColumn("action", function ($row) {
                    $key = "2" === (string) $row->status ? "reopen" : "done";

                    return $this->getTableActionHtml([
                        $key => route("_enquiryStatus", ["key" => $row->uuid]),
                    ]);
                })
                ->rawColumns(["status_badge", "action"])
                ->make(true);
        }

        return view("backend.enquiriesIndex");
    }

    public function enquiryStatus(string $key)
    {
        $row = DB::table("web_enquiry")->where("uuid", $key)->where("deleted", "0")->first();

        if (!$row) {
            return redirect()->back()->with("error", "Enquiry not found.");
        }

        DB::table("web_enquiry")->where("id", $row->id)->update([
            "status" => "2" === (string) $row->status ? "0" : "2",
            "updated_by" => (int) Auth::id(),
            "updated_at" => $this->currentDataTime(),
        ]);

        return redirect()->back();
    }
}
