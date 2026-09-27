<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemarketingService
{
    public function audience(int $day): array
    {
        $target = now()->subDays($day)->toDateString();

        $rows = DB::table("loan_applications")
            ->join("users", "users.id", "=", "loan_applications.user_id")
            ->leftJoin("loan_types", "loan_types.id", "=", "loan_applications.loan_type_id")
            ->select([
                "users.id as user_id",
                "users.name",
                "users.phone",
                "loan_applications.application_no",
                "loan_applications.monthly_income",
                "loan_applications.existing_emi",
                "loan_applications.eligible_amount",
                "loan_types.label as loan_type",
            ])
            ->where("loan_applications.payment_status", 0)
            ->whereNotIn("loan_applications.status", [4, 6])
            ->where("users.status", 1)
            ->where("users.role", 2)
            ->where("users.is_dnd", 0)
            ->whereNull("users.deleted_at")
            ->whereDate("loan_applications.applied_at", $target)
            ->orderBy("loan_applications.id", "desc")
            ->get();

        $unique = [];
        foreach ($rows as $row) {
            $phone = preg_replace("/\D/", "", (string) $row->phone);
            if (10 !== strlen($phone)) {
                continue;
            }
            if (isset($unique[$phone])) {
                continue;
            }
            $row->phone = $phone;
            $unique[$phone] = $row;
        }

        return array_values($unique);
    }

    public function schedule(string $channel): array
    {
        $buckets = [];
        foreach ((array) config("web.cron.slots." . $channel, []) as $day => $expressions) {
            $times = [];
            foreach ((array) $expressions as $expression) {
                $parts = preg_split("/\s+/", trim((string) $expression)) ?: [];
                $times[] = (5 === count($parts) && ctype_digit($parts[0]) && ctype_digit($parts[1]))
                    ? sprintf("%02d:%02d", (int) $parts[1], (int) $parts[0])
                    : (string) $expression;
            }
            sort($times);
            $buckets[] = ["day" => (int) $day, "times" => $times];
        }

        return $buckets;
    }

    public function todayRuns(string $channel, int $day): array
    {
        $rows = DB::table("remarketing_log")
            ->where("cron_type", $channel)
            ->where("cronname", $channel . "-" . $day)
            ->whereDate("rec_date", now()->toDateString())
            ->orderByDesc("id")
            ->get(["msgcount", "msgresponse", "rec_date"]);

        $last = $rows->first();
        $summary = "";
        if ($last) {
            $summary = date("H:i", strtotime((string) $last->rec_date)) . " " . explode("|", (string) $last->msgresponse, 2)[0];
        }

        return [
            "runs" => $rows->count(),
            "sent" => (int) $rows->sum("msgcount"),
            "last" => trim($summary),
        ];
    }

    public function channelStatus(string $channel): array
    {
        $enabled = 1 === (int) config("web.cron.enabled." . $channel, 0);

        if ("sms" === $channel) {
            $configured = "" !== trim((string) config("web.cron.sms.template_id", ""));
            $reason = $configured ? "" : "SMS_REMARKETING_TEMPLATE_ID not set";
        } else {
            $cfg = (array) config("web.cron.whatsapp", []);
            $configured = !empty($cfg["api_url"]) && !empty($cfg["api_key"]) && !empty($cfg["media_url"]);
            $reason = $configured ? "" : "WBBOX_API_URL, WBBOX_API_KEY or WBBOX_MEDIA_URL not set";
        }

        if (!$enabled) {
            $reason = "CRON_" . strtoupper($channel) . "_ENABLED=0";
        }

        return [
            "enabled" => $enabled,
            "configured" => $configured,
            "live" => $enabled && $configured,
            "reason" => $reason,
            "test_numbers" => count($this->testNumbers()),
        ];
    }

    public function sendSmsBatch(int $day): array
    {
        $template = $this->messageTemplate();
        if ("" === $template) {
            return $this->finish("sms", $day, [], "skipped - remarketing_sms option is empty");
        }

        $templateId = (string) config("web.cron.sms.template_id", "");
        if ("" === $templateId) {
            return $this->finish("sms", $day, [], "skipped - SMS_REMARKETING_TEMPLATE_ID is not set");
        }

        $targets = [];
        foreach ($this->audience($day) as $row) {
            $targets[$row->phone] = $this->fillTemplate($template, $this->eligibleAmount($row));
        }
        foreach ($this->testNumbers() as $phone) {
            $targets[$phone] = $this->fillTemplate($template, (float) config("web.cron.default_eligible_amount", 500000));
        }

        $results = [];
        foreach ($targets as $phone => $message) {
            $results[$phone] = $this->postSms($phone, $message, $templateId);
        }

        return $this->finish("sms", $day, $results);
    }

    public function sendWhatsappBatch(int $day): array
    {
        $cfg = config("web.cron.whatsapp", []);
        if (empty($cfg["api_url"]) || empty($cfg["api_key"]) || empty($cfg["media_url"])) {
            return $this->finish("whatsapp", $day, [], "skipped - WBBOX_API_URL, WBBOX_API_KEY or WBBOX_MEDIA_URL is not set");
        }
        if ("unified" === strtolower((string) ($cfg["mode"] ?? "meta")) && empty($cfg["from"])) {
            return $this->finish("whatsapp", $day, [], "skipped - WBBOX_MODE=unified needs WBBOX_FROM (phone number id)");
        }

        $targets = [];
        foreach ($this->audience($day) as $row) {
            $targets[$row->phone] = $row;
        }
        foreach ($this->testNumbers() as $phone) {
            $targets[$phone] = (object) [
                "name" => "Flubbi Test",
                "phone" => $phone,
                "application_no" => "TEST",
                "loan_type" => "Personal Loan",
                "eligible_amount" => (float) config("web.cron.default_eligible_amount", 500000),
                "monthly_income" => 0,
                "existing_emi" => 0,
            ];
        }

        $results = [];
        foreach ($targets as $phone => $row) {
            $results[$phone] = $this->postWhatsapp($row, $day, $cfg);
        }

        return $this->finish("whatsapp", $day, $results);
    }

    private function finish(string $type, int $day, array $results, string $note = ""): array
    {
        $name = $type . "-" . $day;
        $sent = 0;
        $lines = [];

        foreach ($results as $phone => [$ok, $text]) {
            if ($ok) {
                ++$sent;
            }
            $lines[] = $phone . "-" . ($ok ? "" : "FAILED ") . $text;
        }

        $attempted = count($results);
        $response = "" !== $note
            ? $note
            : "ok=" . $sent . " fail=" . ($attempted - $sent) . "|" . implode("|", $lines) . "|";

        DB::table("remarketing_log")->insert([
            "rec_date" => now(),
            "cron_type" => $type,
            "cronname" => $name,
            "msgcount" => $sent,
            "msgresponse" => $response,
        ]);

        Log::info("remarketing cron " . $name . " sent " . $sent . " of " . $attempted . ("" !== $note ? " (" . $note . ")" : ""));

        return ["name" => $name, "count" => $sent, "attempted" => $attempted, "response" => $response];
    }

    private function messageTemplate(): string
    {
        $value = DB::table("web_options")
            ->where("op_group", "sms")
            ->where("op_key", (string) config("web.cron.sms.option_key", "remarketing_sms"))
            ->where("status", "0")
            ->where("deleted", "0")
            ->value("op_value");

        return trim((string) $value);
    }

    private function fillTemplate(string $template, float $amount): string
    {
        return str_replace(
            ["<#preamount>", "<#cronamount>", "<#amount>"],
            $this->indianFormat($amount),
            $template
        );
    }

    private function testNumbers(): array
    {
        $numbers = [];
        foreach ((array) config("web.cron.test_numbers", []) as $number) {
            $clean = preg_replace("/\D/", "", (string) $number);
            if (10 === strlen($clean)) {
                $numbers[] = $clean;
            }
        }

        return $numbers;
    }

    private function eligibleAmount(object $row): float
    {
        $amount = (float) ($row->eligible_amount ?? 0);

        return $amount > 0 ? $amount : (float) config("web.cron.default_eligible_amount", 500000);
    }

    private function indianFormat(float $amount): string
    {
        $digits = (string) (int) round($amount);
        if (strlen($digits) <= 3) {
            return $digits;
        }

        $last = substr($digits, -3);
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", substr($digits, 0, -3));

        return $rest . "," . $last;
    }

    private function postSms(string $phone, string $message, string $templateId): array
    {
        $cfg = config("web.sms.greensms", []);

        $payload = [
            "username" => $cfg["username"] ?? "",
            "apikey" => $cfg["apikey"] ?? "",
            "apirequest" => "Text",
            "sender" => $cfg["sender"] ?? "",
            "route" => (string) config("web.cron.sms.route", "PROMO"),
            "format" => "JSON",
            "message" => $message,
            "mobile" => $phone,
            "TemplateID" => $templateId,
        ];

        [$ok, $body] = $this->curl((string) ($cfg["uri"] ?? ""), $payload, false);
        if (!$ok) {
            return [false, $body];
        }

        $decoded = json_decode($body, true);
        $accepted = is_array($decoded) && isset($decoded["status"]) && "success" === strtolower((string) $decoded["status"]);

        return [$accepted, $body];
    }

    private function bodyParams(object $row): array
    {
        $values = [];
        foreach ((array) config("web.cron.whatsapp.body_params", ["name", "application_no", "amount"]) as $token) {
            $token = (string) $token;
            if (0 === strpos($token, "text:")) {
                $values[] = substr($token, 5);
                continue;
            }
            $values[] = match ($token) {
                "name" => !empty($row->name) ? ucwords((string) $row->name) : "Customer",
                "amount" => $this->indianFormat($this->eligibleAmount($row)),
                "loan_type" => !empty($row->loan_type) ? (string) $row->loan_type : "Loan",
                "application_no" => !empty($row->application_no) ? (string) $row->application_no : "-",
                "phone" => (string) $row->phone,
                default => "",
            };
        }

        return $values;
    }

    private function postWhatsapp(object $row, int $day, array $cfg): array
    {
        $to = "91" . $row->phone;
        $media = trim((string) $cfg["media_url"]);

        $meta = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => (string) $cfg["template"],
                "language" => ["code" => (string) (!empty($cfg["language"]) ? $cfg["language"] : "en")],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            ["type" => "image", "image" => ctype_digit($media) ? ["id" => $media] : ["link" => $media]],
                        ],
                    ],
                    [
                        "type" => "body",
                        "parameters" => array_map(
                            fn (string $text) => ["type" => "text", "text" => $text],
                            $this->bodyParams($row)
                        ),
                    ],
                ],
            ],
            "biz_opaque_callback_data" => "whatsapp-" . $day . ":" . (!empty($row->application_no) ? $row->application_no : $row->phone),
        ];

        if ("unified" === strtolower((string) ($cfg["mode"] ?? "meta"))) {
            unset($meta["to"]);
            $payload = [
                "channel" => "WhatsApp",
                "to" => [$to],
                "from" => (string) $cfg["from"],
                "content" => ["data" => ["templatepayload" => $meta]],
            ];
        } else {
            $payload = $meta;
        }

        $headerName = !empty($cfg["auth_header"]) ? (string) $cfg["auth_header"] : "Authorization";
        $headerValue = "authorization" === strtolower($headerName)
            ? "Bearer " . $cfg["api_key"]
            : (string) $cfg["api_key"];

        [$ok, $body] = $this->curl((string) $cfg["api_url"], $payload, true, [$headerName . ": " . $headerValue]);
        if (!$ok) {
            return [false, $body];
        }

        $decoded = json_decode($body, true);
        $rejected = false;
        if (is_array($decoded)) {
            if (isset($decoded["error"])) {
                $rejected = true;
            } elseif (array_key_exists("status", $decoded) && !is_array($decoded["status"])) {
                $accepted = filter_var($decoded["status"], FILTER_VALIDATE_BOOLEAN)
                    || in_array(strtolower((string) $decoded["status"]), ["success", "sent", "queued", "submitted", "accepted", "ok"], true);
                $rejected = !$accepted;
            } elseif (array_key_exists("success", $decoded)) {
                $rejected = !filter_var($decoded["success"], FILTER_VALIDATE_BOOLEAN);
            }
        }

        return [!$rejected, $body];
    }

    private function curl(string $url, array $payload, bool $json, array $headers = []): array
    {
        if ("" === $url) {
            return [false, "no endpoint configured"];
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json ? json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $payload,
            CURLOPT_TIMEOUT => (int) config("web.cron.timeout_seconds", 20),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array_merge($json ? ["Content-Type: application/json", "Accept: application/json"] : [], $headers),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if (false === $response || "" !== $error) {
            Log::warning("remarketing gateway error", ["url" => $url, "error" => $error]);

            return [false, "gateway error: " . ("" !== $error ? $error : "empty response")];
        }

        $body = trim(preg_replace("/\s+/", " ", (string) $response));
        if ($status < 200 || $status >= 300) {
            Log::warning("remarketing gateway rejected", ["url" => $url, "http" => $status, "body" => substr($body, 0, 300)]);

            return [false, "HTTP " . $status . " " . $body];
        }

        return [true, $body];
    }
}
