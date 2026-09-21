<?php

namespace App\Console\Commands;

use App\Services\RemarketingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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

        $due = null === $this->option("day")
            ? $this->dueBuckets($channel)
            : $this->forcedBuckets($channel, (int) $this->option("day"));

        if ([] === $due) {
            $this->line("nothing due");

            return self::SUCCESS;
        }

        foreach ($due as [$name, $day]) {
            if ($this->option("dry")) {
                $this->line("would run " . $name . "-" . $day);
                continue;
            }

            $result = "sms" === $name
                ? $service->sendSmsBatch($day)
                : $service->sendWhatsappBatch($day);

            $this->line($result["name"] . " -> " . $result["count"] . " number(s)");
        }

        Log::info("remarketing cron finished", ["buckets" => count($due)]);

        return self::SUCCESS;
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
            if (!in_array((int) date($format, $time), $this->expand($field, $bounds), true)) {
                return false;
            }
        }

        return true;
    }

    private function expand(string $field, string $bounds): array
    {
        $values = [];

        foreach (explode(",", $field) as $chunk) {
            $step = 1;
            if (false !== strpos($chunk, "/")) {
                [$chunk, $rawStep] = explode("/", $chunk, 2);
                $step = max(1, (int) $rawStep);
            }
            if ("*" === $chunk) {
                $chunk = $bounds;
            }
            if (false !== strpos($chunk, "-")) {
                [$start, $stop] = explode("-", $chunk, 2);
            } else {
                $start = $stop = $chunk;
                if (1 !== $step) {
                    [$start, $stop] = explode("-", $bounds, 2);
                }
            }
            for ($i = (int) $start; $i <= (int) $stop; $i += $step) {
                $values[] = $i;
            }
        }

        return $values;
    }
}
