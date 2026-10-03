<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsappNotifyService
{
    public function send(string $event, string $phone, array $values, string $reference = ""): array
    {
        try {
            return $this->dispatch($event, $phone, $values, $reference);
        } catch (Throwable $e) {
            Log::warning("whatsapp notify failed", ["event" => $event, "reference" => $reference, "error" => $e->getMessage()]);

            return [false, "exception: " . $e->getMessage()];
        }
    }

    private function dispatch(string $event, string $phone, array $values, string $reference): array
    {
        $cfg = (array) config("web.whatsapp.events." . $event, []);
        if ([] === $cfg || 1 !== (int) ($cfg["enabled"] ?? 0)) {
            return [false, "skipped - event is disabled"];
        }

        $wbbox = new WbboxService();
        if (!$wbbox->isConfigured()) {
            return $this->skipped($event, $reference, "WBBOX_API_URL or WBBOX_API_KEY is not set");
        }

        $media = trim((string) ($cfg["media_url"] ?? ""));
        if ("" === $media) {
            $media = trim((string) config("web.cron.whatsapp.media_url", ""));
        }
        if ("" === $media) {
            return $this->skipped($event, $reference, "no header image configured");
        }

        $phone = preg_replace("/\D/", "", $phone);
        if (10 !== strlen($phone)) {
            return $this->skipped($event, $reference, "phone is not 10 digits");
        }

        $window = (int) ($cfg["dedupe_seconds"] ?? 0);
        if ($window > 0 && "" !== $reference && $this->recentlySent($event . ":" . $reference, $window)) {
            return $this->skipped($event, $reference, "already sent in the last " . $window . " seconds");
        }

        $params = [];
        foreach ((array) ($cfg["body_params"] ?? []) as $token) {
            $params[] = $this->clean((string) ($values[$token] ?? ""));
        }

        [$ok, $body] = $wbbox->sendTemplate(
            $phone,
            (string) $cfg["template"],
            (string) ($cfg["language"] ?? "en"),
            $media,
            $params,
            $event . ":" . $reference,
            (int) config("web.whatsapp.timeout_seconds", 8)
        );

        if ($ok) {
            Log::info("whatsapp " . $event . " sent", ["reference" => $reference]);
        } else {
            Log::warning("whatsapp " . $event . " not delivered", ["reference" => $reference, "response" => substr($body, 0, 300)]);
        }

        return [$ok, $body];
    }

    private function recentlySent(string $key, int $window): bool
    {
        try {
            return !Cache::add("wa:" . $key, 1, $window);
        } catch (Throwable $e) {
            Log::warning("whatsapp dedupe cache unavailable", ["error" => $e->getMessage()]);

            return false;
        }
    }

    private function skipped(string $event, string $reference, string $why): array
    {
        Log::info("whatsapp " . $event . " skipped", ["reference" => $reference, "why" => $why]);

        return [false, "skipped - " . $why];
    }

    private function clean(string $value): string
    {
        $value = strip_tags($value);
        $value = preg_replace("/[\x00-\x1F\x7F]+/u", " ", $value) ?? "";
        $value = trim(preg_replace("/\s+/u", " ", $value) ?? "");
        if (mb_strlen($value) > 200) {
            $value = rtrim(mb_substr($value, 0, 197)) . "...";
        }

        return "" === $value ? "-" : $value;
    }
}
