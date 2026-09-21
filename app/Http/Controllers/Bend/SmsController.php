<?php

namespace App\Http\Controllers\Bend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmsController extends Controller
{
    public function smsMessageIndex(Request $request)
    {
        if ($request->ajax()) {
            $table_data = DB::table('web_options')->where('op_group', 'sms')
                ->where('status', '=', '0')
                ->where('deleted', '=', '0')
                ->orderBy('updated_at', 'desc')->get();
            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("updated_at", function ($row) {
                    return !empty($row->updated_at) ? date("d M Y", strtotime($row->updated_at)) : "-";
                })
                ->addColumn("updated_time", function ($row) {
                    return !empty($row->updated_at) ? date("h:i A", strtotime($row->updated_at)) : "-";
                })
                ->addColumn("op_label", function ($row) {
                    return !empty($row->op_label) ? $row->op_label : "-";
                })
                ->addColumn("op_value", function ($row) {
                    return !empty($row->op_value) ? $row->op_value : "-";
                })
                ->addColumn("edit", function ($row) {
                    return $this->getTableActionHtml([
                        "edit" => route('_smsMessageEdit', [
                            "key" => $row->uuid
                        ])
                    ]);
                })
                ->rawColumns(["edit"])
                ->make(true);
        }
        return view('backend.smsMessageIndex');
    }

    public function smsMessageEdit(Request $request, $key)
    {
        $data = DB::table('web_options')
            ->where('uuid', $key)
            ->first();

        return view('backend.smsMessageEdit', ["data" => $data]);
    }

    public function smsMessageUpdate(Request $request, $key)
    {
        DB::table('web_options')
            ->where('uuid', $key)
            ->update([
                "op_value" => $request->message,
                "updated_at" => Carbon::now()
            ]);

        return redirect()->route('_smsMessageIndex')->with('success', 'SMS Message updated successfully');
    }

    public function remarketingLogsIndex(Request $request)
    {

        if ($request->ajax()) {
            $fdate = $request->fdate;
            $tdate = $request->tdate;
            $fdate = $request->filled("fdate") ? date("Y-m-d 00:00:00", strtotime($request->fdate)) : null;
            $tdate = $request->filled("tdate") ? date("Y-m-d 23:59:59", strtotime($request->tdate)) : null;
            $table_data = DB::table('remarketing_log')
                ->when($fdate, fn ($q) => $q->where('rec_date', '>=', $fdate))
                ->when($tdate, fn ($q) => $q->where('rec_date', '<=', $tdate))
                ->orderBy('id', 'desc')->get();
            return DataTables::of($table_data)
                ->addIndexColumn()
                ->addColumn("rec_date", function ($row) {
                    return !empty($row->rec_date) ? date("d M Y", strtotime($row->rec_date)) : "-";
                })
                ->addColumn("rec_time", function ($row) {
                    return !empty($row->rec_date) ? date("h:i A", strtotime($row->rec_date)) : "-";
                })
                ->addColumn("cron_type", function ($row) {
                    return !empty($row->cron_type) ? ucfirst($row->cron_type) : "-";
                })
                ->addColumn("cronname", function ($row) {
                    return !empty($row->cronname) ? $row->cronname : "-";
                })
                ->addColumn("msgcount", function ($row) {
                    return is_null($row->msgcount) ? "-" : $row->msgcount;
                })
                ->addColumn("details", function ($row) {
                    return $this->getTableActionHtml([
                        "view" => route('_remarketingLogsEdit', [
                            "key" => $row->id
                        ])
                    ]);
                })
                ->rawColumns(["details"])
                ->make(true);
        }
        return view('backend.remarketingLogsIndex');
    }

    public function remarketingLogsEdit()
    {
        $key = request("key");
        $data = DB::table('remarketing_log')->where('id', $key)->first();
        return view('backend.remarketingLogsEdit', ["data" => $data]);
    }

    public function remarketingCycleIndex()
    {
        $sms_crondays = array_map("intval", array_keys((array) config("web.cron.slots.sms", [])));
        $whatsapp_crondays = array_map("intval", array_keys((array) config("web.cron.slots.whatsapp", [])));

        $maxSmsDay = $sms_crondays ? max($sms_crondays) : 0;
        $maxWhatsappDay = $whatsapp_crondays ? max($whatsapp_crondays) : 0;
        $maxDay = max($maxSmsDay, $maxWhatsappDay);

        $applications = DB::table('loan_applications')
            ->join('users', 'users.id', '=', 'loan_applications.user_id')
            ->selectRaw('
            DATE(loan_applications.applied_at) as app_date,
            COUNT(*) as total_applications
        ')
            ->whereBetween('loan_applications.applied_at', [
                Carbon::now()->subDays($maxDay)->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->where('loan_applications.login_type', 'self')
            ->where('loan_applications.payment_status', '0')
            ->where('users.status', '1')
            ->where('users.role', '2')
            ->where('users.is_dnd', '0')
            ->whereNull('users.deleted_at')
            ->groupBy('app_date')
            ->get()
            ->keyBy('app_date');

        $sms_data = [];
        foreach ($sms_crondays as $day) {
            $date = Carbon::now()->subDays($day)->toDateString();
            $sms_data[] = [
                'day' => $day,
                'udate' => Carbon::parse($date)->format('d-m-Y'),
                'applications' => $applications[$date]->total_applications ?? 0
            ];
        }

        $whatsapp_data = [];
        foreach ($whatsapp_crondays as $day) {
            $date = Carbon::now()->subDays($day)->toDateString();
            $whatsapp_data[] = [
                'day' => $day,
                'udate' => Carbon::parse($date)->format('d-m-Y'),
                'applications' => $applications[$date]->total_applications ?? 0
            ];
        }

        return view('backend.remarketingCycleIndex', [
            'sms_data' => $sms_data,
            'whatsapp_data' => $whatsapp_data
        ]);
    }
}
