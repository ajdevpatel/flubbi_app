<?php

return [
    "base_url" => rtrim($_ENV["APP_URL"], "/") . "/",
    "env" => [
        "app_name" => $_ENV["APP_NAME"],
        "app_dev_author" => $_ENV["DEV_AUTHOR"],
    ],
    "backend_slug" => "webapp",
    "is_payment_gateway" => "1",
    "offer_slug" => "offer",
    "frontend_slug" => "user",
    "upload_dir" => "uploads/",
    "media_dir" => [
        "our_partners" => "store/partners/"
    ],
    "loan_word" => [
        "meta_tag" => "Finance Consultant",
        "route" => "finance",
        "view_file" => "finance consultant",
        "user_panel_view_file" => "loan",
    ],
    "base_media" => [
        "favicon" => "favicon.webp"
    ],
    "tax_gst" => [
        "status" => 0,
        "label" => "GST Label",
    ],
    "filter_from_date" => date("Y-m-d", strtotime("-7 day", time())),
    "filter_to_date" => date("Y-m-d", strtotime("+1 day", time())),
    "invoice" => [
        "start_number" => 0,
        "digit_limit" => 6,
        "sac_code" => "997159",
    ],
];
