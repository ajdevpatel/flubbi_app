<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WbboxService
{
    public function isConfigured(): bool
    {
        $cfg = $this->connection();

        if ("" === $cfg["api_url"] || "" === $cfg["api_key"]) {
            return false;
        }

        return "unified" !== $cfg["mode"] || "" !== $cfg["from"];
    }

    public function sendTemplate(string $phone, string $template, string $language, string $media, array $bodyParams, string $callback = "", int $timeout = 0): array
    {
        $cfg = $this->connection();
        $to = "91" . $phone;
        $media = trim($media);

        $components = [];
        if ("" !== $media) {
            $components[] = [
                "type" => "header",
                "parameters" => [
                    ["type" => "image", "image" => ctype_digit($media) ? ["id" => $media] : ["link" => $media]],
                ],
            ];
        }
        if ([] !== $bodyParams) {
            $components[] = [
                "type" => "body",
                "parameters" => array_map(
                    fn ($text) => ["type" => "text", "text" => (string) $text],
                    array_values($bodyParams)
                ),
            ];
        }

        $meta = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $template,
                "language" => ["code" => "" !== $language ? $language : "en"],
            ],
        ];
        if ([] !== $components) {
            $meta["template"]["components"] = $components;
        }
        if ("" !== $callback) {
            $meta["biz_opaque_callback_data"] = $callback;
        }

        if ("unified" === $cfg["mode"]) {
            unset($meta["to"]);
            $payload = [
                "channel" => "WhatsApp",
                "to" => [$to],
                "from" => $cfg["from"],
                "content" => ["data" => ["templatepayload" => $meta]],
            ];
        } else {
            $payload = $meta;
        }

        $headerValue = "authorization" === strtolower($cfg["auth_header"])
            ? "Bearer " . $cfg["api_key"]
            : $cfg["api_key"];

        [$ok, $body] = $this->post($cfg["api_url"], $payload, [$cfg["auth_header"] . ": " . $headerValue], $timeout);
        if (!$ok) {
            return [false, $body];
        }

        return [!$this->isRejected($body), $body];
    }

    private function connection(): array
    {
        $cfg = (array) config("web.cron.whatsapp", []);

        return [
            "api_url" => trim((string) ($cfg["api_url"] ?? "")),
            "api_key" => trim((string) ($cfg["api_key"] ?? "")),
            "auth_header" => !empty($cfg["auth_header"]) ? (string) $cfg["auth_header"] : "Authorization",
            "mode" => strtolower((string) ($cfg["mode"] ?? "meta")),
            "from" => trim((string) ($cfg["from"] ?? "")),
        ];
    }

    private function isRejected(string $body): bool
    {
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            return false;
        }
        if (isset($decoded["error"])) {
            return true;
        }
        if (array_key_exists("status", $decoded) && !is_array($decoded["status"])) {
            $accepted = filter_var($decoded["status"], FILTER_VALIDATE_BOOLEAN)
                || in_array(strtolower((string) $decoded["status"]), ["success", "sent", "queued", "submitted", "accepted", "ok"], true);

            return !$accepted;
        }
        if (array_key_exists("success", $decoded)) {
            return !filter_var($decoded["success"], FILTER_VALIDATE_BOOLEAN);
        }

        return false;
    }

    private function post(string $url, array $payload, array $headers, int $timeout): array
    {
        if ("" === $url) {
            return [false, "no endpoint configured"];
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => $timeout > 0 ? $timeout : (int) config("web.cron.timeout_seconds", 20),
            CURLOPT_CONNECTTIMEOUT => $timeout > 0 ? min($timeout, 10) : 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array_merge(["Content-Type: application/json", "Accept: application/json"], $headers),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if (false === $response || "" !== $error) {
            Log::warning("whatsapp gateway error", ["url" => $url, "error" => $error]);

            return [false, "gateway error: " . ("" !== $error ? $error : "empty response")];
        }

        $body = trim(preg_replace("/\s+/", " ", (string) $response));
        if ($status < 200 || $status >= 300) {
            Log::warning("whatsapp gateway rejected", ["url" => $url, "http" => $status, "body" => substr($body, 0, 300)]);

            return [false, "HTTP " . $status . " " . $body];
        }

        return [true, $body];
    }
}
