<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PincodeService
{
    private const STATE_ALIASES = [
        "andamanandnicobar" => "andamanandnicobarislands",
        "chattisgarh" => "chhattisgarh",
        "dadraandnagarhaveli" => "dadraandnagarhavelianddamananddiu",
        "damananddiu" => "dadraandnagarhavelianddamananddiu",
        "nctofdelhi" => "delhi",
        "newdelhi" => "delhi",
        "orissa" => "odisha",
        "pondicherry" => "puducherry",
        "uttaranchal" => "uttarakhand",
    ];

    private const LADAKH_DISTRICTS = ["leh", "kargil"];

    public function lookup(string $pincode): ?array
    {
        if (1 !== preg_match("/^[1-9][0-9]{5}$/", $pincode)) {
            return null;
        }

        $key = "pincode:" . $pincode;
        $cached = $this->cacheGet($key);
        if (is_array($cached)) {
            return !empty($cached["found"]) ? $cached : null;
        }

        $offices = $this->fetch($pincode);
        if (null === $offices) {
            return null;
        }

        if ([] === $offices) {
            $this->cachePut($key, ["found" => false], now()->addHours((int) config("web.pincode.miss_cache_hours", 24)));

            return null;
        }

        $place = $this->resolve($offices);
        $this->cachePut($key, $place, now()->addDays((int) config("web.pincode.cache_days", 90)));

        return $place;
    }

    private function resolve(array $offices): array
    {
        $state_name = $this->mostCommon(array_column($offices, "State"));
        $in_state = array_filter($offices, fn ($office) => ($office["State"] ?? "") === $state_name);
        $district = $this->mostCommon(array_column($in_state, "District"));

        $state = $this->matchState($state_name, $district);
        $city = $this->cityName($district, $state ? (int) $state->id : 0);

        return [
            "found" => true,
            "city" => $city,
            "state_id" => $state ? (int) $state->id : null,
            "state" => $state ? (string) $state->name : "",
        ];
    }

    private function matchState(string $state_name, string $district)
    {
        $key = $this->normalize($state_name);
        if ("jammuandkashmir" === $key && in_array($this->normalize($district), self::LADAKH_DISTRICTS, true)) {
            $key = "ladakh";
        }
        $key = self::STATE_ALIASES[$key] ?? $key;

        foreach (DB::table("states")->select(["id", "name"])->where("status", 0)->get() as $state) {
            if ($this->normalize((string) $state->name) === $key) {
                return $state;
            }
        }

        return null;
    }

    private function cityName(string $district, int $state_id): string
    {
        $city = preg_replace("/\s*\(.*?\)/", "", $district) ?? "";
        $city = str_replace("&", " and ", $city);
        $city = preg_replace("/[^A-Za-z .'-]/", "", $city) ?? "";
        $city = trim(preg_replace("/\s+/", " ", $city) ?? "");

        if (1 !== preg_match("/^[A-Za-z][A-Za-z .'-]{1,99}$/", $city)) {
            return "";
        }

        if ($state_id > 0) {
            $known = DB::table("districts")
                ->where("state_id", $state_id)
                ->where("status", 0)
                ->whereRaw("LOWER(name) = ?", [strtolower($city)])
                ->value("name");
            if ($known) {
                return (string) $known;
            }
        }

        return $city;
    }

    private function mostCommon(array $values): string
    {
        $values = array_filter(array_map(fn ($value) => trim((string) $value), $values), fn ($value) => "" !== $value);
        if ([] === $values) {
            return "";
        }

        $counts = array_count_values($values);
        arsort($counts);

        return (string) array_key_first($counts);
    }

    private function normalize(string $name): string
    {
        return preg_replace("/[^a-z]/", "", str_replace("&", "and", strtolower($name))) ?? "";
    }

    private function fetch(string $pincode): ?array
    {
        $url = str_replace("{pincode}", $pincode, (string) config("web.pincode.api_url", "https://api.postalpincode.in/pincode/{pincode}"));
        $timeout = (int) config("web.pincode.timeout_seconds", 6);

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => min($timeout, 4),
            CURLOPT_HTTPHEADER => ["Accept: application/json"],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if (false === $response || "" !== $error || 200 !== $status) {
            Log::warning("pincode lookup failed", ["pincode" => $pincode, "http" => $status, "error" => $error]);

            return null;
        }

        $result = json_decode((string) $response, true)[0] ?? null;
        if (!is_array($result) || !isset($result["Status"])) {
            Log::warning("pincode lookup gave an unexpected response", ["pincode" => $pincode, "body" => substr((string) $response, 0, 200)]);

            return null;
        }

        $offices = $result["PostOffice"] ?? null;
        if ("Success" !== $result["Status"] || !is_array($offices)) {
            return [];
        }

        return array_values(array_filter($offices, "is_array"));
    }

    private function cacheGet(string $key)
    {
        try {
            return Cache::get($key);
        } catch (Throwable $e) {
            return null;
        }
    }

    private function cachePut(string $key, array $value, $until): void
    {
        try {
            Cache::put($key, $value, $until);
        } catch (Throwable $e) {
            Log::warning("pincode cache unavailable", ["error" => $e->getMessage()]);
        }
    }
}
