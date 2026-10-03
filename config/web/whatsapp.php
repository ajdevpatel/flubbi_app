<?php

return [
    "timeout_seconds" => 8,

    "status_events" => [
        3 => "loan_approved",
    ],

    "events" => [
        "payment_failed" => [
            "enabled" => (int) env("WA_PAYMENT_FAILED_ENABLED", 1),
            "template" => env("WA_PAYMENT_FAILED_TEMPLATE", "payment_failed"),
            "language" => "en",
            "media_url" => env("WA_PAYMENT_FAILED_MEDIA_URL", ""),
            "body_params" => ["name", "amount", "application_no", "reason"],
            "dedupe_seconds" => 30,
        ],
        "application_received" => [
            "enabled" => (int) env("WA_APPLICATION_RECEIVED_ENABLED", 1),
            "template" => env("WA_APPLICATION_RECEIVED_TEMPLATE", "application_received"),
            "language" => "en",
            "media_url" => env("WA_APPLICATION_RECEIVED_MEDIA_URL", ""),
            "body_params" => ["name", "application_no", "loan_amount"],
            "dedupe_seconds" => 3600,
        ],
        "loan_approved" => [
            "enabled" => (int) env("WA_LOAN_APPROVED_ENABLED", 1),
            "template" => env("WA_LOAN_APPROVED_TEMPLATE", "loan_approval_remarks"),
            "language" => "en",
            "media_url" => env("WA_LOAN_APPROVED_MEDIA_URL", ""),
            "body_params" => ["name", "application_no"],
            "message" => "Dear {{1}},\n\nWe are pleased to inform you that your loan application (Ref: {{2}}) has been approved, subject to final verification and lender policies.\n\nOur team will share the next steps shortly.\n\nRegards,\nFlubbi Fintech LLP",
            "dedupe_seconds" => 30,
        ],
    ],
];
