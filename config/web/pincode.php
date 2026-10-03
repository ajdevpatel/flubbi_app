<?php

return [
    "api_url" => env("PINCODE_API_URL", "https://api.postalpincode.in/pincode/{pincode}"),
    "timeout_seconds" => 6,
    "cache_days" => 90,
    "miss_cache_hours" => 24,
];
