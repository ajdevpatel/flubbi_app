<?php

$common_steps = [
    ["Verify your mobile", "Open Personal Loan or Business Loan from the Services menu, enter your name and mobile number and confirm it with the OTP."],
    ["Fill your details", "Add your email, PIN code, city and state, then tell us if you are salaried or self employed."],
    ["Income details", "Enter your monthly income, CIBIL band, running EMI and the amount you need."],
    ["See your offer", "Check the pre-approved amount you qualify for and pick a tenure."],
];

return [
    "self-login" => [
        "label" => "Self Login",
        "icon" => "bi-person-check",
        "title" => "Apply on your own with Self Login",
        "intro" => "Watch how to check your eligibility, pay the one time platform fee and apply directly on the lending partner's website.",
        "video_key" => "self_video",
        "steps" => array_merge($common_steps, [
            ["Choose Self Login", "On the \"How would you like to proceed?\" step, select Self Login."],
            ["Pay the platform fee", "Pay the one time platform fee online. The lending partner list unlocks right after payment."],
            ["Pick a lending partner", "Select one partner from the list. This choice is final for the application."],
            ["Apply on the partner's website", "The partner's secure website opens in a new tab. Complete the application there with your PAN and Aadhaar handy."],
        ]),
        "notes" => [
            "Your selected partner and its apply link stay saved under My Account > My Applications.",
            "Payment receipts are available under My Account > Transactions.",
        ],
    ],
    "hire-agent" => [
        "label" => "Hire Agent",
        "icon" => "bi-headset",
        "title" => "Let a Flubbi agent handle it with Hire Agent",
        "intro" => "Watch how to check your eligibility, pay the one time service fee and hand your application over to a dedicated Flubbi agent.",
        "video_key" => "consultant_video",
        "steps" => array_merge($common_steps, [
            ["Choose Hire Agent", "On the \"How would you like to proceed?\" step, select Hire Agent."],
            ["Pay the service fee", "Pay the one time service fee online to confirm your application."],
            ["Get a call from your agent", "A Flubbi agent calls you within one working day of payment confirmation."],
            ["Upload your documents", "Upload your documents under My Account > Documents so the agent can start right away."],
        ]),
        "notes" => [
            "Your agent picks the best-fit lender for your profile and manages documents, follow-ups and lender coordination.",
            "You get updates by SMS, email and in My Account until disbursal.",
        ],
    ],
];
