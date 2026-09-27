<?php

return [
    "timezone" => "Asia/Kolkata",

    "enabled" => [
        "sms" => (int) env("CRON_SMS_ENABLED", 1),
        "whatsapp" => (int) env("CRON_WHATSAPP_ENABLED", 1),
    ],

    "slots" => [
        "sms" => [
            0 => ["0 11 * * *", "0 16 * * *", "0 21 * * *"],
            1 => ["0 11 * * *", "0 17 * * *"],
            2 => ["0 12 * * *", "0 18 * * *"],
            4 => ["0 13 * * *", "0 19 * * *"],
            7 => ["0 14 * * *", "0 20 * * *"],
            11 => ["0 15 * * *", "0 21 * * *"],
            15 => ["0 16 * * *", "0 20 * * *"],
        ],
        "whatsapp" => [
            0 => ["0 9 * * *", "0 13 * * *", "0 21 * * *"],
            1 => ["30 9 * * *", "0 14 * * *", "30 21 * * *"],
            2 => ["0 10 * * *", "0 15 * * *", "0 22 * * *"],
            3 => ["30 10 * * *", "0 16 * * *", "30 22 * * *"],
            6 => ["0 11 * * *", "0 17 * * *", "0 23 * * *"],
            10 => ["30 11 * * *", "0 18 * * *", "0 20 * * *"],
            12 => ["0 12 * * *", "0 19 * * *", "30 20 * * *"],
            16 => ["30 12 * * *", "30 19 * * *"],
        ],
    ],

    "sms" => [
        "template_id" => env("SMS_REMARKETING_TEMPLATE_ID", ""),
        "route" => env("SMS_REMARKETING_ROUTE", "PROMO"),
        "option_key" => "remarketing_sms",
    ],

    "whatsapp" => [
        "api_url" => env("WBBOX_API_URL", ""),
        "mode" => env("WBBOX_MODE", "meta"),
        "from" => env("WBBOX_FROM", ""),
        "api_key" => env("WBBOX_API_KEY", ""),
        "auth_header" => env("WBBOX_AUTH_HEADER", "Authorization"),
        "template" => env("WBBOX_TEMPLATE", "loan_offer_generated_remarks"),
        "language" => env("WBBOX_LANGUAGE", "en"),
        "media_url" => env("WBBOX_MEDIA_URL", ""),
        "body_params" => ["name", "application_no", "amount"],
    ],

    "test_numbers" => [],

    "default_eligible_amount" => 500000,
    "timeout_seconds" => 20,
];
