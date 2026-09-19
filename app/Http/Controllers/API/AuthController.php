<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function sendOTPTextMessage(int $phone = 8866442200, int $otp = 123456)
    {
        $res_data = [
            "status" => false,
            "message" => "OTP sent failed, please try again",
        ];

        try {
            $sender_id = 'FLIUBI';
            $template_id = '1707176863325131867';
            $msg = "Hello, " . $otp . " is the OTP (One Time Password) to register your mobile number. (Do not share it with anyone). FLUBBI FINTECH";
            $username = 'flubbintechsms';
            $apikey = 'FD031-4C929';
            $uri = 'http://login.greensms.in/sms-panel/api/http/index.php';
            $data = array(
                'username' => $username,
                'apikey' => $apikey,
                'apirequest' => 'Text',
                'sender' => $sender_id,
                'route' => 'TRANS',
                'format' => 'JSON',
                'message' => $msg,
                'mobile' => $phone,
                'TemplateID' => $template_id,
            );

            $ch = curl_init($uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            $resp = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            //$res = json_encode(compact('resp', 'error'));

            if (!empty($error)) {
                $res_data = [
                    "status" => false,
                    "message" => "OTP sent failed, please try again",
                ];
            } else {
                $res = json_decode($resp, true);
                if (isset($res["status"]) && $res["status"] == "success") {
                    $res_data = [
                        "status" => true,
                        "message" => "OTP sent successfully",
                    ];
                } else {
                    $res_data = [
                        "status" => false,
                        "message" => "OTP sent failed, please try again",
                    ];
                }
            }
        } catch (Exception $e) {
            $res_data = [
                "status" => false,
                "message" => "OTP sent failed - " . $e->getMessage(),
            ];
        }

        return $res_data;
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->all()[0],
            ], 200);
        }

        $phone = $request->phone;
        $otp = rand(100000, 999999);

        if($phone == 8866442200) {
            $otp = 123456;
        } else {
            $otp_res = $this->sendOTPTextMessage($phone, $otp);

            if (true != $otp_res["status"]) {
                return response()->json([
                    "status" => false,
                    "message" => $otp_res["message"],
                ], 200);
            }

            $lastOtp = DB::table('otp_logs')
                ->where('phone', $phone)
                ->where('is_used', '0')
                ->update([
                    'is_used' => 1,
                    'updated_at' => now()
                ]);

            DB::table('otp_logs')->insert([
                'phone'      => $phone,
                'otp'        => $otp,
                'is_used'    => 0,
                'expires_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

       

        /*
        $lastOtp = collect(DB::table('otp_logs')
            ->where('phone', $phone)
            ->where('is_used', '0')
            ->orderByDesc('id')
            ->first())->toArray();

        if ($lastOtp && now()->diffInSeconds($lastOtp->created_at) < 60) {
            return response()->json([
                'status' => false,
                'message' => 'Please wait before requesting another OTP'
            ], 422);
        }

        if ($lastOtp) {
            $otp = isset($lastOtp["otp"]) ? $lastOtp["otp"] : $otp;
        } else {
            DB::table('otp_logs')->insert([
                'phone'      => $phone,
                'otp'        => $otp,
                'is_used'    => 0,
                'expires_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        */

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully'
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10',
            'otp'   => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->all()[0],
            ], 200);
        }

        // Allow bypass for special test number and OTP
        if (!($request->phone == 8866442200 && $request->otp == 123456)) {
            $otpRow = DB::table('otp_logs')
                ->where('phone', $request->phone)
                ->where('otp', $request->otp)
                ->where('is_used', '0')
                ->first();

            if (!$otpRow) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired OTP'
                ], 200);
            }

            DB::table('otp_logs')
                ->where('id', $otpRow->id)
                ->update([
                    'is_used' => 1,
                    'updated_at' => now()
                ]);
        }

        $user = DB::table('users')
            ->where('phone', $request->phone)
            ->first();

        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'uuid' => Str::uuid(),
                'phone' => $request->phone,
                'role' => 2,
                'status' => 1,
                'mobile_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $user = DB::table('users')->where('id', $userId)->first();
        } else {
            if ($user->status == 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is blocked'
                ], 200);
            }
        }

        DB::table('users')->where('id', $user->id)->update([
            'deleted_at' => null
        ]);

        $token = User::find($user->id)->createToken('app')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => collect($user)->toArray()
        ], 200);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }
}
