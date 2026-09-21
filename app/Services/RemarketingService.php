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
            ->select([
                "users.id as user_id",
                "users.name",
                "users.phone",
                "loan_applications.application_no",
                "loan_applications.monthly_income",
                "loan_applications.existing_emi",
                "loan_applications.eligible_amount",
            ])
            ->where("loan_applications.login_type", "self")
            ->where("loan_applications.payment_status", 0)
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
        if (empty($cfg["api_key"]) || empty($cfg["campaign_name"])) {
            return $this->finish("whatsapp", $day, [], "skipped - AISENSY_KEY or AISENSY_CAMPAIGN is not set");
        }

        $targets = [];
        foreach ($this->audience($day) as $row) {
            $targets[$row->phone] = [
                "name" => $row->name ? ucwords((string) $row->name) : "Customer",
                "amount" => $this->eligibleAmount($row),
            ];
        }
        foreach ($this->testNumbers() as $phone) {
            $targets[$phone] = [
                "name" => "Flubbi Test",
                "amount" => (float) config("web.cron.default_eligible_amount", 500000),
            ];
        }

        $results = [];
        foreach ($targets as $phone => $target) {
            $results[$phone] = $this->postWhatsapp($phone, $target["name"], $target["amount"], $cfg);
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
        if ($amount > 0) {
            return $amount;
        }

        $income = (float) ($row->monthly_income ?? 0);
        $emi = (float) ($row->existing_emi ?? 0);
        $rate = (float) config("web.cron.fallback_interest_rate", 12.5);
        $default = (float) config("web.cron.default_eligible_amount", 500000);

        if ($income <= 0) {
            return $default;
        }

        $capacity = floor(($income * 0.40) - $emi);
        $monthly = floor(($default + ($default * ($rate / 100)) * 6) / 72);
        if ($capacity <= 0 || $monthly <= 0) {
            return $default;
        }

        $amount = floor(($default * $capacity) / $monthly);
        if ($amount < 200000) {
            return 195000;
        }
        if ($amount > 850000) {
            return 875000;
        }

        return round($amount);
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

    private function postWhatsapp(string $phone, string $name, float $amount, array $cfg): array
    {
        $formatted = $this->indianFormat($amount);

        $payload = [
            "apiKey" => $cfg["api_key"],
            "campaignName" => $cfg["campaign_name"],
            "destination" => "91" . $phone,
            "userName" => $name,
            "templateParams" => [$name, $formatted],
            "tags" => (array) ($cfg["tags"] ?? []),
            "attributes" => ["eligibleAmount" => $formatted],
        ];

        if (!empty($cfg["media"]["url"])) {
            $payload["media"] = $cfg["media"];
        }

        [$ok, $body] = $this->curl((string) $cfg["api_url"], $payload, true);
        if (!$ok) {
            return [false, $body];
        }

        $decoded = json_decode($body, true);
        $rejected = is_array($decoded) && (isset($decoded["errorCode"]) || isset($decoded["errorMessage"])
            || (isset($decoded["success"]) && !filter_var($decoded["success"], FILTER_VALIDATE_BOOLEAN)));

        return [!$rejected, $body];
    }

    private function curl(string $url, array $payload, bool $json): array
    {
        if ("" === $url) {
            return [false, "no endpoint configured"];
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json ? json_encode($payload) : $payload,
            CURLOPT_TIMEOUT => (int) config("web.cron.timeout_seconds", 20),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $json ? ["Content-Type: application/json"] : [],
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
