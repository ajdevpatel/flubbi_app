<?php

return [
    "greensms" => [
        "uri" => "http://login.greensms.in/sms-panel/api/http/index.php",
        "username" => "flubbintechsms",
        "apikey" => "FD031-4C929",
        "sender" => "FLIUBI",
        "route" => "TRANS",
        "template_id" => "1707176863325131867",
        // DLT approved text - only {otp} may change
        "template" => "Hello, {otp} is the OTP (One Time Password) to register your mobile number. (Do not share it with anyone). FLUBBI FINTECH",
    ],

    "otp" => [
        "expiry_minutes" => 5,
        "cooldown_seconds" => 60,
        "max_per_hour" => 5,
        "max_attempts" => 5,

        "test_phone" => "8866442200",
        "test_otp" => "123456",
    ],
];
