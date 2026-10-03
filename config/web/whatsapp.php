<?php

return [
    "timeout_seconds" => 8,
    "executive_contact" => env("WA_LOAN_EXECUTIVE_CONTACT", ""),

    "status_events" => [
        2 => "loan_under_review",
        3 => "loan_approved",
        4 => "loan_rejected",
        5 => "loan_disbursed",
        7 => "document_pending",
    ],

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
        "loan_approved" => [
            "enabled" => (int) env("WA_LOAN_APPROVED_ENABLED", 1),
            "template" => env("WA_LOAN_APPROVED_TEMPLATE", "loan_approval_remarks"),
            "language" => "en",
            "media_url" => env("WA_LOAN_APPROVED_MEDIA_URL", ""),
            "body_params" => ["name", "application_no"],
            "message" => "Dear {{1}},\n\nWe are pleased to inform you that your loan application (Ref: {{2}}) has been approved, subject to final verification and lender policies.\n\nOur team will share the next steps shortly.\n\nRegards,\nFlubbi Fintech LLP",
            "dedupe_seconds" => 30,
        ],
        "loan_rejected" => [
            "enabled" => (int) env("WA_LOAN_REJECTED_ENABLED", 1),
            "template" => env("WA_LOAN_REJECTED_TEMPLATE", "application_rejected_lender"),
            "language" => "en",
            "media_url" => env("WA_LOAN_REJECTED_MEDIA_URL", ""),
            "body_params" => ["name", "application_no"],
            "message" => "Dear {{1}},\n\nThis is an update regarding your loan application (Ref: {{2}}).\n\nAfter reviewing the information provided, your application is currently not eligible for approval as per the lender's assessment criteria.\n\nThank you for your interest.\n\nRegards,\nFlubbi Fintech LLP",
            "dedupe_seconds" => 30,
        ],
        "loan_disbursed" => [
            "enabled" => (int) env("WA_LOAN_DISBURSED_ENABLED", 1),
            "template" => env("WA_LOAN_DISBURSED_TEMPLATE", "disbursment_remarks"),
            "language" => "en",
            "media_url" => env("WA_LOAN_DISBURSED_MEDIA_URL", ""),
            "body_params" => ["name", "application_no"],
            "message" => "Dear {{1}},\n\nThis is an update regarding your approved loan application (Ref: {{2}}).\n\nThe disbursement process has been initiated. We will notify you once the funds have been successfully processed.\n\nThank you,\nFlubbi Fintech LLP",
            "dedupe_seconds" => 30,
        ],
        "document_pending" => [
            "enabled" => (int) env("WA_DOCUMENT_PENDING_ENABLED", 1),
            "template" => env("WA_DOCUMENT_PENDING_TEMPLATE", "documents_pending_reminder_01"),
            "language" => "en",
            "media_url" => env("WA_DOCUMENT_PENDING_MEDIA_URL", ""),
            "body_params" => ["name", "application_no", "loan_kind", "executive_contact"],
            "message" => "Dear {{1}},\n\nYour {{3}} loan application is currently on hold due to pending document submission.\n\nPlease provide the required documents at the earliest to avoid delays in processing.\n\nApplication Number: {{2}}\n\nFor assistance, Please Contact our Loan Executive {{4}}.\n\nThank you,\nFlubbi Finech LLP",
            "dedupe_seconds" => 30,
        ],
        "loan_under_review" => [
            "enabled" => (int) env("WA_LOAN_UNDER_REVIEW_ENABLED", 1),
            "template" => env("WA_LOAN_UNDER_REVIEW_TEMPLATE", "applicaiton_under_process_remarks"),
            "language" => "en",
            "media_url" => env("WA_LOAN_UNDER_REVIEW_MEDIA_URL", ""),
            "body_params" => ["name", "application_no"],
            "message" => "Dear {{1}},\n\nYour loan application of (Reference: {{2}}) has successfully moved to the next stage of processing.\n\nOur team will contact you if any additional information or documents are required.\n\nThank you,\nFlubbi Fintech LLP",
            "dedupe_seconds" => 30,
        ],
    ],
];
