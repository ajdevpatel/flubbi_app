<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    public function isTestPhone(string $phone): bool
    {
        return $phone === (string) config("web.sms.otp.test_phone");
    }

    public function issue(string $phone): array
    {
        $cooldown = (int) config("web.sms.otp.cooldown_seconds", 60);
        $max_per_hour = (int) config("web.sms.otp.max_per_hour", 5);
        $expiry = (int) config("web.sms.otp.expiry_minutes", 5);

        $is_test = $this->isTestPhone($phone);

        $last = $is_test ? null : DB::table("otp_logs")
            ->where("phone", $phone)
            ->orderByDesc("id")
            ->first();

        if ($last && !empty($last->created_at)) {
            $elapsed = (int) Carbon::parse($last->created_at)->diffInSeconds(now());
            if ($elapsed < $cooldown) {
                $wait = $cooldown - $elapsed;
                return [
                    "status" => false,
                    "message" => "Please wait " . $wait . " seconds before requesting another OTP.",
                    "cooldown" => $wait,
                ];
            }
        }

        $recent = $is_test ? 0 : DB::table("otp_logs")
            ->where("phone", $phone)
            ->where("created_at", ">=", now()->subHour())
            ->count();

        if ($recent >= $max_per_hour) {
            return [
                "status" => false,
                "message" => "Too many OTP requests for this number. Please try again after an hour.",
            ];
        }

        $otp = random_int(100000, 999999);

        if ($is_test) {
            $otp = (int) config("web.sms.otp.test_otp", 123456);
        } else {
            $sent = $this->sendSms($phone, $otp);
            if (true !== $sent["status"]) {
                return $sent;
            }
        }

        DB::table("otp_logs")
            ->where("phone", $phone)
            ->where("is_used", 0)
            ->update([
                "is_used" => 1,
                "updated_at" => now(),
            ]);

        DB::table("otp_logs")->insert([
            "phone" => $phone,
            "otp" => $otp,
            "is_used" => 0,
            "expires_at" => now()->addMinutes($expiry),
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        return [
            "status" => true,
            "message" => "OTP sent to +91 " . $phone,
            "cooldown" => $cooldown,
        ];
    }

    public function verify(string $phone, string $otp): array
    {
        $key = "otp-verify:" . $phone;

        if (RateLimiter::tooManyAttempts($key, (int) config("web.sms.otp.max_attempts_per_phone", 10))) {
            DB::table("otp_logs")->where("phone", $phone)->where("is_used", 0)->update(["is_used" => 1, "updated_at" => now()]);
            return [
                "status" => false,
                "burned" => true,
                "message" => "Too many wrong attempts for this number. Please request a new OTP after a few minutes.",
            ];
        }

        $row = DB::table("otp_logs")
            ->where("phone", $phone)
            ->where("otp", $otp)
            ->where("is_used", 0)
            ->where("expires_at", ">", now())
            ->orderByDesc("id")
            ->first();

        if (!$row) {
            RateLimiter::hit($key, 300);
            return [
                "status" => false,
                "message" => "Invalid or expired OTP. Please check the code or request a new one.",
            ];
        }

        RateLimiter::clear($key);

        DB::table("otp_logs")
            ->where("id", $row->id)
            ->update([
                "is_used" => 1,
                "updated_at" => now(),
            ]);

        return [
            "status" => true,
            "message" => "Mobile number verified",
        ];
    }

    public function sendSms(string $phone, int $otp): array
    {
        $cfg = config("web.sms.greensms", []);
        $failed = [
            "status" => false,
            "message" => "OTP could not be sent right now. Please try again.",
        ];

        try {
            $data = [
                "username" => $cfg["username"] ?? "",
                "apikey" => $cfg["apikey"] ?? "",
                "apirequest" => "Text",
                "sender" => $cfg["sender"] ?? "",
                "route" => $cfg["route"] ?? "TRANS",
                "format" => "JSON",
                "message" => str_replace("{otp}", (string) $otp, $cfg["template"] ?? "{otp}"),
                "mobile" => $phone,
                "TemplateID" => $cfg["template_id"] ?? "",
            ];

            $ch = curl_init($cfg["uri"] ?? "");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $resp = curl_exec($ch);
            $error = curl_error($ch);

            if (!empty($error)) {
                Log::warning("OTP SMS gateway error", ["phone" => $phone, "error" => $error]);
                return $failed;
            }

            $res = json_decode((string) $resp, true);
            if (isset($res["status"]) && "success" === $res["status"]) {
                return ["status" => true, "message" => "OTP sent successfully"];
            }

            Log::warning("OTP SMS gateway rejected", ["phone" => $phone, "response" => (string) $resp]);
        } catch (\Throwable $e) {
            Log::warning("OTP SMS gateway exception", ["phone" => $phone, "error" => $e->getMessage()]);
        }

        return $failed;
    }
}
