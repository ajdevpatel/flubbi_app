<?php

/*
|--------------------------------------------------------------------------
| Website (pl-service / bl-service) platform fees
|--------------------------------------------------------------------------
|
| These are used by the WEBSITE loan flow only. The mobile app keeps using
| App\Http\Controllers\API\LoanController::payment_data(), so changing the
| values here never changes what the app charges.
|
*/

return [
    "gst_rate" => 18,

    "login_type" => [
        "self" => [
            "label" => "Self Login",
            "base_amount" => 299,
            "note" => "One time platform fee. Bank list with direct apply links is unlocked right after payment.",
        ],
        "consultant" => [
            "label" => "Hire Agent",
            "base_amount" => 499,
            "note" => "One time service fee. A dedicated Flubbi agent handles your application end to end.",
        ],
    ],

    "currency" => "INR",
    "payment_gateway" => "razorpay",
];
