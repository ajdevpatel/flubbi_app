<?php

$allow_mail = [
    "gmail.com",
    "yahoo.com",
    "rediffmail.com",
    "icloud.com",
    "hotmail.com",
    "outlook.com",
    "rocketmail.com",
];

/*
if ("local" == $_ENV["APP_ENV"]) {
    array_push($allow_mail, "yopmail.com");
} */

return [
    "is_active" => 1,
    "logo" => $_ENV["APP_URL"] . "/store/logo.png",
    "mail" => "flubbi@gmail.com",
    "phone" => "+91 85302 41214",
    "wapp_phone" => "+91 85302 41214",
    "location" => " 512, MBC, Lajamni Chowk, Mota Varachha, Surat, Gujarat - 394105",
    // "phone" => "+91 81603 00254",
    // "wapp_phone" => "+91 81603 00254",
    // "location" => " 71, Omkar Bunglows, Beside Adarsh Nagar Gate, Amroli, Surat, Gujarat - 394107",

    "footer_links" => [
        [
            "label" => "About us",
            "url" => "_aboutusPost",
        ],
        [
            "label" => "Privacy Policy",
            "url" => "_privacyPolicyPost",
        ],
        [
            "label" => "Terms & Conditions",
            "url" => "_termsAndConditionsPost",
        ],
        [
            "label" => "Contact us",
            "url" => "_contactusPost",
        ],
    ],
    "footer_contact_us" => [
        "text" => "If you have questions about your order, you can email us at",
        "mail" => ""
    ],
    "default_subject" => $_ENV["APP_NAME"],
    "default_body_text" => $_ENV["APP_NAME"],
    "footer_warm_regards_heading" => "Warm Regards",
    "footer_warm_regards_text" => "Team " . $_ENV["APP_NAME"],
    "copyright_text" => $_ENV["APP_NAME"] . " All rights reserved.",
    "copyright_payment_img" => $_ENV["APP_URL"] . "/assets/img/payment.png",
    "allow_mail" => $allow_mail
];
