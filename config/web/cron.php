<?php

return [
    "timezone" => "Asia/Kolkata",

    "enabled" => [
        "sms" => 1,
        "whatsapp" => 1,
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
        "api_url" => "https://backend.aisensy.com/campaign/t1/api/v2",
        "api_key" => env("AISENSY_KEY", ""),
        "campaign_name" => env("AISENSY_CAMPAIGN", ""),
        "tags" => ["Get Offer"],
        "media" => [
            "url" => env("AISENSY_MEDIA_URL", ""),
            "filename" => env("AISENSY_MEDIA_FILE", ""),
        ],
    ],

    "test_numbers" => [],

    "default_eligible_amount" => 500000,
    "fallback_interest_rate" => 12.5,
    "timeout_seconds" => 20,
];
