<?php

return [
    "timeout_seconds" => 8,

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
    ],
];
