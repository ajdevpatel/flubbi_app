<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentReminderService
{
    public function run(?Carbon $now = null): array
    {
        $cfg = (array) config("web.whatsapp.reminders", []);
        if (1 !== (int) ($cfg["enabled"] ?? 0)) {
            return ["processed" => 0, "sent" => 0, "note" => "reminders disabled"];
        }

        $schedule = (array) ($cfg["schedule"] ?? []);
        if ([] === $schedule) {
            return ["processed" => 0, "sent" => 0, "note" => "no schedule"];
        }

        $now = $now ?: Carbon::now();
        $start_from = trim((string) ($cfg["start_from"] ?? ""));
        $notify = new WhatsappNotifyService();

        $sent = 0;
        $processed = 0;
        foreach ($this->audience($start_from) as $row) {
            ++$processed;
            try {
                $sent += $this->processApplication($row, $schedule, $now, $notify);
            } catch (Throwable $e) {
                Log::warning("payment reminder failed", ["application" => $row->application_no ?? "?", "error" => $e->getMessage()]);
            }
        }

        return ["processed" => $processed, "sent" => $sent];
    }

    private function audience(string $start_from)
    {
        $query = DB::table("loan_applications as la")
            ->join("payment_transactions as pt", function ($join) {
                $join->on("pt.loan_application_id", "=", "la.id")->where("pt.status", "success");
            })
            ->join("users as u", "u.id", "=", "la.user_id")
            ->leftJoin("loan_types as lt", "lt.id", "=", "la.loan_type_id")
            ->where("la.payment_status", 1)
            ->where("u.status", 1)
            ->where("u.role", 2)
            ->where("u.is_dnd", 0)
            ->whereNull("u.deleted_at")
            ->select([
                "la.id as app_id",
                "la.application_no",
                "la.eligible_amount",
                "la.login_type",
                "lt.label as loan_type",
                "u.name",
                "u.phone",
                "pt.updated_at as paid_at",
            ]);

        if ("" !== $start_from) {
            $query->where("pt.updated_at", ">=", $start_from);
        }

        return $query->orderBy("la.id")->get();
    }

    private function processApplication(object $row, array $schedule, Carbon $now, WhatsappNotifyService $notify): int
    {
        $phone = preg_replace("/\D/", "", (string) $row->phone);
        if (10 !== strlen($phone)) {
            return 0;
        }

        $elapsed = Carbon::parse($row->paid_at)->diffInMinutes($now, false);
        if ($elapsed < 0) {
            return 0;
        }

        $values = $this->values($row);
        $sent = 0;

        foreach ($schedule as $step) {
            $event = (string) ($step["event"] ?? "");
            $after = (int) ($step["after_minutes"] ?? 0);
            $window = (int) ($step["window_minutes"] ?? 0);

            if ("" === $event || $elapsed < $after || $elapsed > $after + $window) {
                continue;
            }
            if (1 !== (int) config("web.whatsapp.events." . $event . ".enabled", 0)) {
                continue;
            }
            if ($this->alreadySent((int) $row->app_id, $event)) {
                continue;
            }

            [$ok] = $notify->send($event, $phone, $values, (string) $row->application_no);
            if ($ok) {
                $this->record((int) $row->app_id, $event, $phone);
                ++$sent;
            }
        }

        return $sent;
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
            Log::info("wa reminder already recorded", ["application_id" => $application_id, "event" => $event]);
        }
    }

    private function values(object $row): array
    {
        $amount = (float) ($row->eligible_amount ?? 0);
        if ($amount <= 0) {
            $amount = (float) config("web.cron.default_eligible_amount", 500000);
        }
        $loan_type = (string) ($row->loan_type ?? "");

        return [
            "name" => !empty($row->name) ? ucwords((string) $row->name) : "Customer",
            "application_no" => (string) $row->application_no,
            "loan_amount" => "₹" . $this->indianFormat((int) round($amount)),
            "loan_kind" => trim(preg_replace("/\s+loan$/i", "", $loan_type) ?? $loan_type),
            "executive_contact" => (string) config("web.whatsapp.executive_contact", ""),
            "whatsapp_contact" => (string) config("web.store_data.phone", ""),
            "support_email" => (string) config("web.store_data.support_mail", ""),
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
}
