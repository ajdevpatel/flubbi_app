<?php

namespace App\Console\Commands;

use App\Services\RemarketingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class DevCronJob extends Command
{
    protected $signature = "app:cronjob
        {--channel= : Limit the run to sms or whatsapp}
        {--day= : Run this day bucket right now and ignore the slot times}
        {--dry : Show the buckets that are due without sending anything}";

    protected $description = "flubbi fintech - remarketing cron job";

    private const CHANNELS = ["sms", "whatsapp"];

    public function handle(RemarketingService $service): int
    {
        $channel = $this->option("channel");
        if (null !== $channel && !in_array($channel, self::CHANNELS, true)) {
            $this->error("--channel must be sms or whatsapp");

            return self::FAILURE;
        }

        $day = $this->option("day");
        if (null !== $day && !ctype_digit((string) $day)) {
            $this->error("--day must be a whole number of days, for example --day=0 or --day=7");

            return self::FAILURE;
        }

        $due = null === $day
            ? $this->dueBuckets($channel)
            : $this->forcedBuckets($channel, (int) $day);

        if ([] === $due) {
            $this->line("nothing due");

            return self::SUCCESS;
        }

        $failed = 0;
        foreach ($due as [$name, $bucket]) {
            if ($this->option("dry")) {
                $this->line("would run " . $name . "-" . $bucket);
                continue;
            }

            try {
                $result = "sms" === $name
                    ? $service->sendSmsBatch($bucket)
                    : $service->sendWhatsappBatch($bucket);

                $this->line($result["name"] . " -> sent " . $result["count"] . " of " . $result["attempted"]);
            } catch (Throwable $e) {
                ++$failed;
                $this->error($name . "-" . $bucket . " failed: " . $e->getMessage());
                Log::error("remarketing cron bucket failed", [
                    "bucket" => $name . "-" . $bucket,
                    "error" => $e->getMessage(),
                ]);
            }
        }

        Log::info("remarketing cron finished", ["buckets" => count($due), "failed" => $failed]);

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function dueBuckets(?string $only): array
    {
        $time = time();
        $due = [];

        foreach (self::CHANNELS as $channel) {
            if (null !== $only && $only !== $channel) {
                continue;
            }
            if (1 != config("web.cron.enabled." . $channel)) {
                continue;
            }

            foreach ((array) config("web.cron.slots." . $channel, []) as $day => $expressions) {
                foreach ((array) $expressions as $expression) {
                    if ($this->isTimeCron($time, $expression)) {
                        $due[] = [$channel, (int) $day];
                        break;
                    }
                }
            }
        }

        return $due;
    }

    private function forcedBuckets(?string $only, int $day): array
    {
        $due = [];
        foreach (self::CHANNELS as $channel) {
            if (null !== $only && $only !== $channel) {
                continue;
            }
            $due[] = [$channel, $day];
        }

        return $due;
    }

    public function isTimeCron(int $time, string $cron): bool
    {
        $parts = preg_split("/\s+/", trim($cron), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (5 !== count($parts)) {
            return false;
        }

        $now = Carbon::createFromTimestamp($time, (string) config("web.cron.timezone", "Asia/Kolkata"));

        $fields = [
            ["i", "0-59"],
            ["G", "0-23"],
            ["j", "1-31"],
            ["n", "1-12"],
            ["w", "0-6"],
        ];

        foreach ($fields as $index => [$format, $bounds]) {
            $field = $parts[$index];
            if ("*" === $field) {
                continue;
            }
            $current = (int) $now->format($format);
            if (!in_array($current, $this->expand($field, $bounds, 4 === $index), true)) {
                return false;
            }
        }

        return true;
    }

    private function expand(string $field, string $bounds, bool $weekday = false): array
    {
        [$min, $max] = array_map("intval", explode("-", $bounds, 2));
        $values = [];

        foreach (explode(",", $field) as $chunk) {
            $step = 1;
            if (false !== strpos($chunk, "/")) {
                [$chunk, $rawStep] = explode("/", $chunk, 2);
                $step = max(1, (int) $rawStep);
            }

            if ("*" === $chunk) {
                $start = $min;
                $stop = $max;
            } elseif (false !== strpos($chunk, "-")) {
                [$start, $stop] = array_map("intval", explode("-", $chunk, 2));
            } else {
                $start = (int) $chunk;
                $stop = 1 === $step ? $start : $max;
            }

            for ($i = $start; $i <= $stop; $i += $step) {
                $values[] = $weekday && 7 === $i ? 0 : $i;
            }
        }

        return $values;
    }
}
