<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class UserController extends Controller
{
    public function getStates()
    {
        $states = DB::table('states')
            ->where('status', 0)
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $states
        ]);
    }

    public function getDistricts(Request $request)
    {
        $states = collect(DB::table('districts')
            ->where('state_id', $request->state_id ?? 0)
            ->where('status', 0)
            ->orderBy('name')
            ->get())->toArray() ?? [];

        return response()->json([
            'status' => true,
            'data' => $states
        ]);
    }

    public function getProfile(Request $request)
    {
        $userId = $request->user()->id;

        $profile = DB::table('users as u')
            ->leftJoin('states as s', 's.id', '=', 'u.state_id')
            ->leftJoin('districts as d', 'd.id', '=', 'u.city_id')
            ->leftJoin('loan_applications as la', 'la.user_id', '=', 'u.id')
            ->where('u.id', $userId)
            ->select([
                'u.*',
                's.name as state_name',
                'd.name as district_name',
                'la.dob',
                'la.pan_card',
                DB::raw("CONCAT('" . config('app.url') . "', '/uploads/', u.profile_pic) as profile_pic"),
            ])
            ->orderBy("la.id", "desc")
            ->first();

        return response()->json([
            'status' => true,
            'data' => $profile
        ]);
    }

    public function updateProfile(Request $request)
    {
        $userId = $request->user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'gender' => 'required|in:male,female,other',
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:districts,id',
            'city' => 'required|string|max:100',
            'pincode' => 'required|digits_between:5,10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->all()[0],
            ], 200);
        }

        DB::table('users')
            ->where('id', $request->user()->id)
            ->update([
                'name' => $request->name,
                'gender' => $request->gender,
                'email' => $request->email,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function updateProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->all()[0],
            ], 200);
        }

        $user = $request->user();

        $file = $request->file('profile_pic');
        $extension = $file->getClientOriginalExtension();
        $random = rand(100000, 999999);
        $fileName = 'profile' . '_' . $random . '.' . $extension;

        $uploadPath = public_path('uploads/');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $fileName);

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'profile_pic' => $fileName,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile image updated successfully'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $user->tokens()->delete();

        DB::table('users')->where('id', $user->id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully'
        ]);
    }

    public function dashboardData(Request $request)
    {
        $user = $request->user();

        $loan_applications = collect(DB::table('loan_applications as la')
            ->leftJoin('loan_types as lt', 'la.loan_type_id', '=', 'lt.id')
            ->leftJoin('loan_status as ls', 'la.status', '=', 'ls.id')
            ->leftJoin('payment_transactions as pm', 'pm.loan_application_id', '=', 'la.id')
            ->select(
                'la.id as loan_id',
                'la.application_no',
                'la.created_at',
                'la.dob',
                'lt.label as loan_type',
                'ls.label as loan_status',
                'pm.status as tz_status',
                'pm.id as tz_id',
            )
            ->where('la.user_id', $user->id)
            ->orderBy('la.id', 'desc')
            ->first())->toArray() ?? [];

        $user->pan_card = $loan_applications->pan_card ?? "";
        $user->dob = $loan_applications->dob ?? "";
        $user->profile_pic = $user->profile_pic ? config('app.url') . "/uploads/" . $user->profile_pic : "";

        $dashboard = [
            "loan_type" => "3+",
            "partners" => "25+",
            "min_rate" => "12%",
        ];

        return response()->json([
            'status' => true,
            'message' => 'Dashboard data fetched successfully',
            'user' => $user,
            'loan_applications' => $loan_applications,
            'dashboard' => $dashboard,
        ]);
    }

    #######################################################################

    public function getSupportReasons()
    {
        $scores = DB::table('support_reasons')
            ->select('id', 'label')
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'support reasons list fetched successfully',
            'data' => $scores
        ]);
    }

    public function createSupportTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason_id'  => 'required|exists:support_reasons,id',
            'message'  => 'required|string',
            'priority' => 'nullable|in:low,medium,high'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();

        $ticketNo = 'ST-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        DB::table('support_tickets')->insert([
            'ticket_no' => $ticketNo,
            'user_id'   => $user->id,
            'reason_id'   => $request->reason_id,
            'message'   => $request->message,
            'status'    => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Support ticket created successfully',
            'data'    => [
                'ticket_no' => $ticketNo
            ]
        ], 200);
    }

    public function supportTicketList(Request $request)
    {
        $user = $request->user();

        $tickets = DB::table('support_tickets as t')
            ->leftJoin('support_reasons as r', 'r.id', '=', 't.reason_id')
            ->where('t.user_id', $user->id)
            ->orderBy('t.id', 'desc')
            ->select(
                't.id',
                't.ticket_no',
                't.reason_id',
                'r.label as reason_label',
                't.priority',
                't.status',
                't.created_at'
            )
            ->paginate(10);

        $support_data = collect(DB::table('options')->whereIn('key', ['support_mail', 'support_phone'])->get());

        return response()->json([
            'status' => true,
            'data'   => $tickets,
            'support_data' => $support_data
        ], 200);
    }

    #######################################################################

    public function notificationsList(Request $request)
    {
        $user = $request->user();

        $query = DB::table('notifications')->where('user_id', $user->id);

        if ($request->get('is_read') === '0') {
            $query->where('is_read', 0);
        } else if ($request->get('is_read') === '1') {
            $query->where('is_read', 1);
        }

        $notifications = $query->orderBy('id', 'desc')->paginate(10);

        $notifications->getCollection()->transform(function ($item) {
            $item->time_ago = Carbon::parse($item->created_at)->diffForHumans();
            return $item;
        });

        $unreadCount = DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'status' => true,
            'unread_count' => $unreadCount,
            'data' => $notifications
        ]);
    }

    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $unreadCount = DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', 0)
            ->count() ?? 0;

        return response()->json([
            'status' => true,
            'count' => $unreadCount,
        ]);
    }

    public function notificationsMarkRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'nullable|integer|exists:notifications,id',
            'mark_all' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();
        if ($request->mark_all === true) {
            DB::table('notifications')
                ->where('user_id', $user->id)
                ->where('is_read', 0)
                ->update([
                    'is_read' => 1,
                    'updated_at' => now()
                ]);
            return response()->json([
                'status' => true,
                'message' => 'All notifications marked as read'
            ]);
        }

        if ($request->filled('notification_id')) {
            DB::table('notifications')
                ->where('user_id', $user->id)
                ->where('id', $request->notification_id)
                ->update([
                    'is_read' => 1,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request parameters'
        ], 422);
    }

    public function notificationCreate(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:255',
            'message'        => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $id = DB::table('notifications')->insertGetId([
            'user_id'        => $user->id,
            'title'          => $request->title,
            'message'        => $request->message,
            'is_read'        => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Notification created successfully',
            'notification_id' => $id
        ], 201);
    }

    public function saveFcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'fcm_token' => $request->token,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'FCM token saved successfully'
        ]);
    }

    #######################################################################

    public function transactionAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'base_amount' => 'required|numeric|min:0',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'gst_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_gateway' => 'required|in:razorpay,phonepe,paytm',
            'gateway_payment_id' => 'required',
            'gateway_transaction_id' => 'required',
            'gateway_response' => 'required|array',
            'status' => 'required|in:pending,success,failed,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();

        DB::table('transaction_history')->insert([
            'user_id' => $user->id,
            'loan_application_id' => $request->loan_application_id,
            'gateway_response' => json_encode([
                "data" => $request->all(),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid loan application'
            ], 403);
        }


        DB::beginTransaction();
        try {
            $existing_tz = DB::table('payment_transactions')
                ->where('loan_application_id', $loan->id)
                ->first();

            $tz_data = [
                'base_amount' => $request->base_amount,
                'gst_percentage' => $request->gst_percentage,
                'gst_amount' => $request->gst_amount,
                'total_amount' => $request->total_amount,
                'payment_gateway' => $request->payment_gateway,
                'gateway_payment_id' => $request->gateway_payment_id,
                'gateway_transaction_id' => $request->gateway_transaction_id,
                'gateway_response' => json_encode([
                    "data" => $request->gateway_response,
                ]),
                'status' => $request->status,
                'updated_at' => now(),
            ];

            if ($existing_tz) {
                DB::table('payment_transactions')->where("id", $existing_tz->id)->update($tz_data);
                $tz_id = $existing_tz->id;
            } else {
                $tz_data["user_id"] = $user->id;
                $tz_data["loan_application_id"] = $request->loan_application_id;
                $tz_data["created_at"] = now();
                $tz_id = DB::table('payment_transactions')->insertGetId($tz_data);
            }

            if ("success" == $request->status) {
                DB::table('loan_applications')->where("id", $request->loan_application_id)->update([
                    "status" => 2,
                    "payment_status" => 1,
                    "step" => 8,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction created successfully',
                'transaction_id' => $tz_id,
                'loan_application_id' => $request->loan_application_id,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Transaction failed',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function transactionsList(Request $request)
    {
        $user = $request->user();

        $transactions = DB::table('payment_transactions as pt')
            ->leftJoin('loan_applications as la', 'la.id', '=', 'pt.loan_application_id')
            ->leftJoin('loan_types as lt', 'la.loan_type_id', '=', 'lt.id')
            ->select(
                'pt.id as payment_id',
                'pt.*',
                'la.application_no',
                'lt.label as loan_type',
            )
            ->where('pt.user_id', $user->id)
            ->orderBy('pt.id', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Transaction history fetched successfully',
            'data' => $transactions
        ], 200);
    }

    #######################################################################

    public function ourPartners()
    {
        $partners = DB::table('our_partners')->select([
            "our_partners.*",
            DB::raw("CONCAT('" . config("app.url") . "','/partners/',our_partners.logo) as logo_url"),
        ])->where('status', 0)->orderBy('id', 'asc')->get();

        return response()->json([
            'status' => true,
            'data'   => $partners
        ], 200);
    }

    public function loginVideo()
    {
        $type_video = collect(DB::table('options')->whereIn('key', ['consultant_video', 'self_video'])->get());

        return response()->json([
            'status' => true,
            'message' => 'Video fetched successfully',
            'video' => $type_video,
        ], 200);
    }

    public function supportData()
    {
        $support_data = collect(DB::table('options')->whereIn('key', ['support_mail', 'support_phone'])->get());

        return response()->json([
            'status' => true,
            'message' => 'Support data fetched successfully',
            'data' => $support_data,
        ], 200);
    }
}
