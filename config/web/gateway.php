<?php

return [
    "payment_gateway" => [  #Please don't change index & values
        "0" => "other",
        "1" => "razorpay",
        "2" => "phonepe",
        "3" => "cashfree",
        "4" => "zaakpay",
        "5" => "easebuzz",
    ],
    "default_gateway" => [
        "main_gateway" => "2",
        "offer_gateway" => "2",
    ],
    "credentials" => [
        "phonepe" => [
            "app" => [
                "prefix" => "phonepe_",
            ],
            "production" => [
                "salt_index" => "1",
                "merchant_id" => "M22HEPBCS4ZAS",
                "salt_key" => "29b4f11c-0b52-4b46-b4f9-0357bed3e8e9",
                "end_point" => "/pg/v1/pay",
                "app_url" => "https://api.phonepe.com/apis/hermes/pg/v1/pay",
                "status_url" => "https://api.phonepe.com/apis/hermes/pg/v1/status/",
            ],
            "local" => [
                "salt_index" => "1",
                "merchant_id" => "SUTARIYAUAT",
                "salt_key" => "34d4357a-c6ac-4d2f-b89d-9aeb27ed2e84",
                "end_point" => "/pg/v1/pay",
                "app_url" => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
                "status_url" => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/",
            ],
        ],
        "razorpay" => [
            "app" => [],
            "production" => [],
            "local" => [],
        ]
    ]
];
