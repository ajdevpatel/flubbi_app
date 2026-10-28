<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsappCycleService
{
    private const REMOTE_URL = "https://flubbi.in/cycle/index.php";
    private const ACCESS_TOKEN = "adc775b457d1c1724902790ffb0c6797d3de11fd";
    private const SITE_DOMAIN = "flubbi.in";
    private const REMOTE_TIMEOUT = 8;
    private const CACHE_MINUTES = 60;

    public function run(?Carbon $now = null): array
    {
        if (1 !== (int) config("web.whatsapp_cycle.enabled", 0)) {
            return ["slots" => 0, "sent" => 0, "note" => "cycle disabled"];
        }

        $wbbox = new WbboxService();
        if (!$wbbox->isConfigured()) {
            return ["slots" => 0, "sent" => 0, "note" => "wbbox not configured"];
        }

        $tz = (string) config("web.whatsapp_cycle.timezone", "Asia/Kolkata");
        $now = ($now ? $now->copy() : Carbon::now())->setTimezone($tz);
        $current = $now->format("H:i");

        $due = array_values(array_filter($this->schedule(), fn ($slot) => $this->slotTime($slot) === $current));
        if ([] === $due) {
            return ["slots" => 0, "sent" => 0];
        }

        $media = trim((string) config("web.cron.whatsapp.media_url", ""));
        $sent = 0;
        foreach ($due as $slot) {
            try {
                $sent += $this->sendSlot($slot, $now, $wbbox, $media);
            } catch (Throwable $e) {
                Log::warning("whatsapp cycle slot failed", ["slot" => ($slot["day"] ?? "?") . ":" . ($slot["time"] ?? "?"), "error" => $e->getMessage()]);
            }
        }

        return ["slots" => count($due), "sent" => $sent];
    }

    public function schedule(): array
    {
        $cacheFile = storage_path("app/wa_cycle_cache.json");
        clearstatcache(true, $cacheFile);

        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_MINUTES * 60) {
            return $this->parse(json_decode((string) @file_get_contents($cacheFile), true));
        }

        $remote = $this->fetchRemote(self::REMOTE_URL);
        if ([] !== $remote) {
            @file_put_contents($cacheFile, json_encode($remote, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $remote;
        }

        if (is_file($cacheFile)) {
            @unlink($cacheFile);
            Log::warning("whatsapp cycle: schedule file unavailable - cache cleared, nothing sent");
        }

        return [];
    }

    private function fetchRemote(string $url): array
    {
        $timeout = self::REMOTE_TIMEOUT;
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => min($timeout, 5),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "X-Access-Token: " . self::ACCESS_TOKEN,
                "X-Site-Domain: " . self::SITE_DOMAIN,
            ],
        ]);
        $body = curl_exec($curl);
        $error = curl_error($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if (false === $body || "" !== $error || $status < 200 || $status >= 300) {
            Log::warning("whatsapp cycle remote fetch failed", ["url" => $url, "http" => $status, "error" => $error]);

            return [];
        }

        return $this->parse(json_decode((string) $body, true));
    }

    private function parse($decoded): array
    {
        if (is_array($decoded) && isset($decoded["schedule"]) && is_array($decoded["schedule"])) {
            $decoded = $decoded["schedule"];
        }

        return $this->validate(is_array($decoded) ? $decoded : []);
    }

    private function validate(array $schedule): array
    {
        $clean = [];
        foreach ($schedule as $slot) {
            if (!is_array($slot)) {
                continue;
            }
            $day = (int) ($slot["day"] ?? 0);
            $time = $this->slotTime($slot);
            $template = trim((string) ($slot["template"] ?? ""));
            $params = array_values(array_filter((array) ($slot["params"] ?? []), "is_string"));
            if ($day < 0 || "" === $time || "" === $template || [] === $params) {
                continue;
            }
            $clean[] = ["day" => $day, "time" => $time, "template" => $template, "params" => $params];
        }

        return $clean;
    }

    private function slotTime(array $slot): string
    {
        $time = trim((string) ($slot["time"] ?? ""));
        if (1 !== preg_match("/^([01]?\d|2[0-3]):([0-5]\d)$/", $time, $m)) {
            return "";
        }

        return sprintf("%02d:%02d", (int) $m[1], (int) $m[2]);
    }

    private function sendSlot(array $slot, Carbon $now, WbboxService $wbbox, string $media): int
    {
        $day = (int) $slot["day"];
        $slotKey = "cycle:" . $day . ":" . $slot["time"];
        $sent = 0;

        foreach ($this->audience($day, $now) as $row) {
            if ($this->alreadySent((int) $row->app_id, $slotKey)) {
                continue;
            }
            $params = $this->resolveParams($slot["params"], $this->values($row));
            [$ok] = $wbbox->sendTemplate((string) $row->phone, $slot["template"], "en", $media, $params, $slotKey . ":" . $row->application_no, (int) config("web.whatsapp.timeout_seconds", 8));
            if ($ok) {
                $this->record((int) $row->app_id, $slotKey, (string) $row->phone);
                ++$sent;
            }
        }

        foreach ($this->testNumbers() as $number) {
            $event = "ct:" . $number . ":" . $slot["template"];
            if ($this->alreadySent(0, $event)) {
                continue;
            }
            $params = $this->resolveParams($slot["params"], $this->sampleValues($now));
            [$ok] = $wbbox->sendTemplate($number, $slot["template"], "en", $media, $params, "cycletest:" . $slot["template"], (int) config("web.whatsapp.timeout_seconds", 8));
            if ($ok) {
                $this->record(0, $event, $number);
                ++$sent;
            }
        }

        return $sent;
    }

    private function audience(int $day, Carbon $now)
    {
        $target = $now->copy()->subDays($day)->toDateString();

        $rows = DB::table("loan_applications as la")
            ->join("users as u", "u.id", "=", "la.user_id")
            ->leftJoin("loan_types as lt", "lt.id", "=", "la.loan_type_id")
            ->where("la.payment_status", 0)
            ->whereNotIn("la.status", [4, 6])
            ->where("u.status", 1)
            ->where("u.role", 2)
            ->whereNull("u.deleted_at")
            ->whereDate("la.applied_at", $target)
            ->select([
                "la.id as app_id",
                "la.application_no",
                "la.eligible_amount",
                "la.applied_at",
                "lt.label as loan_type",
                "u.name",
                "u.phone",
            ])
            ->orderBy("la.id")
            ->get();

        $unique = [];
        foreach ($rows as $row) {
            $phone = preg_replace("/\D/", "", (string) $row->phone);
            if (10 !== strlen($phone) || isset($unique[$phone])) {
                continue;
            }
            $row->phone = $phone;
            $unique[$phone] = $row;
        }

        return array_values($unique);
    }

    private function testNumbers(): array
    {
        $numbers = [];
        foreach ((array) config("web.whatsapp_cycle.test_numbers", []) as $number) {
            $clean = preg_replace("/\D/", "", (string) $number);
            if (10 === strlen($clean)) {
                $numbers[$clean] = true;
            }
        }

        return array_keys($numbers);
    }

    private function resolveParams(array $tokens, array $values): array
    {
        $params = [];
        foreach ($tokens as $token) {
            if (0 === strpos($token, "text:")) {
                $params[] = substr($token, 5);
                continue;
            }
            $params[] = (string) ($values[$token] ?? "-");
        }

        return $params;
    }

    private function values(object $row): array
    {
        $amount = (float) ($row->eligible_amount ?? 0);
        if ($amount <= 0) {
            $amount = (float) config("web.whatsapp_cycle.default_eligible_amount", 500000);
        }
        $loan_type = (string) ($row->loan_type ?? "");

        return [
            "name" => !empty($row->name) ? ucwords((string) $row->name) : "Customer",
            "application_no" => (string) $row->application_no,
            "amount" => "\u{20B9}" . $this->indianFormat((int) round($amount)),
            "amount_plain" => $this->indianFormat((int) round($amount)),
            "applied_date" => !empty($row->applied_at) ? date("d M Y", strtotime((string) $row->applied_at)) : date("d M Y"),
            "loan_type" => "" !== $loan_type ? trim(preg_replace("/\s+loan$/i", "", $loan_type) ?? $loan_type) : "Personal",
        ];
    }

    private function sampleValues(Carbon $now): array
    {
        $amount = (float) config("web.whatsapp_cycle.default_eligible_amount", 500000);

        return [
            "name" => "Flubbi Test",
            "application_no" => "LN-SAMPLE",
            "amount" => "\u{20B9}" . $this->indianFormat((int) round($amount)),
            "amount_plain" => $this->indianFormat((int) round($amount)),
            "applied_date" => $now->format("d M Y"),
            "loan_type" => "Personal",
        ];
    }

    private function indianFormat(int $amount): string
    {
        $digits = (string) $amount;
        if (strlen($digits) <= 3) {
            return $digits;
        }

        $last = substr($digits, -3);
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", substr($digits, 0, -3));

        return $rest . "," . $last;
    }

    private function alreadySent(int $application_id, string $event): bool
    {
        return DB::table("wa_reminders")
            ->where("loan_application_id", $application_id)
            ->where("event", $event)
            ->exists();
    }

    private function record(int $application_id, string $event, string $phone): void
    {
        try {
            DB::table("wa_reminders")->insert([
                "loan_application_id" => $application_id,
                "event" => $event,
                "phone" => $phone,
                "sent_at" => now(),
            ]);
        } catch (Throwable $e) {
            Log::info("wa cycle already recorded", ["application_id" => $application_id, "event" => $event]);
        }
    }
}
