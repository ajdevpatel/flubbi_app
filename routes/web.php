<?php

use Illuminate\Support\Facades\Route;

##### Backend #####
use App\Http\Middleware\BendAuth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Bend\UsersController;
use App\Http\Controllers\Bend\CustomersController;
use App\Http\Controllers\Bend\LeadsController;
use App\Http\Controllers\Bend\ApplicationsController;
use App\Http\Controllers\Bend\SettingsController;
use App\Http\Controllers\Bend\SupportController;
use App\Http\Controllers\Bend\TransactionsController;
use App\Http\Controllers\Bend\MarketingController;
use App\Http\Controllers\Bend\ReportsController;
use App\Http\Controllers\Bend\OurPartnersController;
use App\Http\Controllers\Bend\SearchCustomersController;
use App\Http\Controllers\Bend\SmsController;

##### Frontend #####
use App\Http\Middleware\MinifyHtml;
use App\Http\Middleware\FendAuth;
use App\Http\Controllers\Fend\HomeController;
use App\Http\Controllers\Fend\LoanServiceController;
use App\Http\Controllers\Fend\UserPanelController;



Route::controller(AuthController::class)->group(function () {
    Route::match(["get", "post"], "/" . config("web.webapp.backend_slug"), "backendLogin")->name("_backendLogin");
    Route::get("/" . config("web.webapp.backend_slug") . "/logout", "backendLogout")->name("_backendLogout");
});


Route::prefix(config("web.webapp.backend_slug"))->middleware(BendAuth::class)->group(function () {
    Route::get("/dashboard", [UsersController::class, "dashboardIndex"])->name("_backendDashboardIndex");

    Route::controller(SearchCustomersController::class)->group(function () {

        // Page Load
        Route::get('/search-customers', 'searchCustomersIndex')
            ->name('_searchCustomersIndex');

        // AJAX search
        Route::get('/search-customers/search', 'searchCustomerByPhone')
            ->name('_searchCustomerByPhone');
    });

    Route::controller(LeadsController::class)->group(function () {
        Route::match(["get", "post"], "/leads", "leadEnquiryIndex")->name("_leadEnquiryIndex");
        Route::match(["get", "post"], "/leads/{key}", "leadEnquiryEdit")->where("key", "[a-zA-Z0-9-]+")->name("_leadEnquiryEdit");
    });

    Route::controller(ApplicationsController::class)->group(function () {
        Route::match(["get", "post"], "/application", "postIndex")->name("_applicationIndex");
        Route::post("/application/remove-document", "removeDocumentPost")->name("_removeApplicationDocument");
        Route::get("/application/{key}", "viewPostIndex")->where("key", "[a-zA-Z0-9-]+")->name("_applicationView");
        Route::post("/application/{key}", "addStatusPost")->where("key", "[a-zA-Z0-9-]+")->name("_applicationAddStatus");

        // Refactored loan routes
        Route::match(["get", "post"], "/personal-loan", "postIndex")->defaults("type", "personal")->name("_personalLoanIndex");
        Route::get("/personal-loan/{key}", "viewPostIndex")->where("key", "[a-zA-Z0-9-]+")->name("_personalLoanView");
        Route::post("/personal-loan/{key}", "addStatusPost")->where("key", "[a-zA-Z0-9-]+")->name("_personalLoanAddStatus");

        Route::match(["get", "post"], "/business-loan", "postIndex")->defaults("type", "business")->name("_businessLoanIndex");
        Route::get("/business-loan/{key}", "viewPostIndex")->where("key", "[a-zA-Z0-9-]+")->name("_businessLoanView");
        Route::post("/business-loan/{key}", "addStatusPost")->where("key", "[a-zA-Z0-9-]+")->name("_businessLoanAddStatus");

        Route::match(["get", "post"], "/credit-card", "postIndex")->defaults("type", "credit-card")->name("_creditCardIndex");
        Route::get("/credit-card/{key}", "viewPostIndex")->where("key", "[a-zA-Z0-9-]+")->name("_creditCardView");
        Route::post("/credit-card/{key}", "addStatusPost")->where("key", "[a-zA-Z0-9-]+")->name("_creditCardAddStatus");
    });

    Route::controller(CustomersController::class)->group(function () {
        Route::match(['get', 'post'], '/customers', 'postIndex')
            ->defaults('type', 'personal')
            ->defaults('login_type', 'self')
            ->name('_customersIndex');
        Route::match(["get", "post"], "/customer/add", "postStore")->name("_customersAddIndex");
        Route::get("/customers/{key}", "postEdit")->where("key", "[a-z0-9-]+")->name("_customersEdit");
        Route::put("/customers/{key}", "postUpdate")->where("key", "[a-z0-9-]+")->name("_customersUpdate");
        Route::delete("/customers/{key}", "postDelete")->where("key", "[a-z0-9-]+")->name("_customersDelete");
        Route::get("/documents/{key}/{type}", "documentsUpdate")->where("key", "[a-z0-9-]+")->name("_documentsUpdate");
        Route::get("/documents/{key}", "documentsVerifyStatusUpdate")->where("key", "[a-z0-9-]+")->name("_documentsVerifyStatusUpdate");
        Route::match(["get", "post"], "/dnd-customers", "dndPostIndex")->name("_dndCustomersIndex");
        Route::match(["get", "post"], "/dnd-customers/{key}", "dndAddRemovePostIndex")->where("key", "[a-z0-9-]+")->name("_dndAddRemoveIndex");


        ########################################################################
        Route::get("/user-invoice/{key}", "postInvoice")->where("key", "[a-z0-9-]+")->name("_customersInvoice");
        #Route::get("/customers/de-active/{key}/{status}", "postDeactiveStatus")->where("key", "[a-z0-9-]+")->name("_customersDeactiveStatus");
        ########################################################################
    });

    Route::controller(SettingsController::class)->group(function () {
        Route::match(["get", "post"], "/settings", "webOptionPostIndex")->name("_webOptionPostIndex");
        Route::get("/message-onfiguration", "messageConfigurationPostIndex")->name("_messageConfigurationPostIndex");
        Route::match(["get", "post"], "/message-configuration/{key}", "messageConfigurationEdit")->name("_messageConfigurationEdit");
    });

    Route::controller(SupportController::class)->group(function () {
        Route::match(["get", "post"], "/support", "supportPostIndex")->name("_supportPostIndex");
        Route::match(["get", "post"], "/support/{key}", "supportPostEdit")->where("key", "[a-z0-9-]+")->name("_supportPostEdit");
    });

    Route::controller(TransactionsController::class)->group(function () {
        Route::match(["get", "post"], "/transactions", "transactionsPostIndex")->name("_transactionsIndex");
        Route::match(["get", "post"], "/subscriptions", "subscriptionsPostIndex")->name("_subscriptionsIndex");
    });

    Route::controller(MarketingController::class)->group(function () {
        Route::match(["get", "post"], "/marketing-manual", "manualMarketingIndex")->name("_manualMarketingIndex");
        Route::post("/import-manual", "addManualMarketingPost")->name("_addManualMarketingPost");


        Route::match(["get", "post"], "/otp-logs", "otpLogsIndex")->name("_otpLogsIndex");
    });

    Route::controller(ReportsController::class)->group(function () {
        Route::match(["get", "post"], "/report-gst", "gstReportIndex")->name("_gstReportIndex");
    });

    // controller for sms message, remarketing logs and remarketing cycle
    Route::controller(SmsController::class)->group(function () {
        Route::match(["get", "post"], "/sms-message", "smsMessageIndex")
            ->name("_smsMessageIndex");

        Route::get("/sms-message/{key}", "smsMessageEdit")
            ->where("key", "[A-Za-z0-9\-]+")
            ->name("_smsMessageEdit");

        Route::post("/sms-message/{key}", "smsMessageUpdate")
            ->where("key", "[A-Za-z0-9\-]+")
            ->name("_smsMessageUpdate");

        Route::match(["get", "post"], "/remarketing-logs", "remarketingLogsIndex")->name("_remarketingLogsIndex");
        Route::match(["get", "post"], "/remarketing-logs/{key}", "remarketingLogsEdit")->where("key", "[a-z0-9-]+")->name("_remarketingLogsEdit");

        Route::match(["get", "post"], "/remarketing-cycle", "remarketingCycleIndex")->name("_remarketingCycleIndex");
    });

    Route::controller(OurPartnersController::class)->group(function () {
        Route::match(["get", "post"], "/roi-packages", "roiPackagesIndex")->name("_roiPackagesIndex");
        Route::put("/roi-packages", "roiPackagesStore")->name("_roiPackagesStore");
    });
});



Route::middleware([FendAuth::class, MinifyHtml::class])->group(function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get("/", "homeIndex")->name("_homeIndex");
        Route::get("/xxx", "xxxPost")->name("_xxxPost");
        Route::get("/404", "index404")->name("_index404");
        Route::get("/about-us", "aboutusPost")->name("_aboutusPost");
        Route::get("/privacy-policy", "privacyPolicyPost")->name("_privacyPolicyPost");
        Route::get("/refund-policy", "refundPolicyPost")->name("_refundPolicyPost");
        Route::get("/shipping-policy", "shippingPolicyPost")->name("_shippingPolicyPost");
        Route::get("/terms-and-conditions", "termsAndConditionsPost")->name("_termsAndConditionsPost");
        Route::get("/disclaimer", "disclaimerPost")->name("_disclaimerPost");
        Route::get("/faq", "faqPost")->name("_faqPost");
        Route::match(["get", "post"], "/contact-us", "contactusPost")->name("_contactusPost");
        Route::post("/add-lead", "loanLeadPost")->name("_loanLeadPost");
        Route::get("/emi-calculator", "emiCalculatorPostIndex")->name("_emiCalculatorIndex");
        Route::get("help-and-support", "homeIndex")->name("_helpAndSupportPost");

        // Account Deletion Routes
        Route::get("/delete-account", "accountDeleteIndex")->name("_accountDeleteIndex");
        Route::post("/delete-account-send-otp", "accountDeleteSendOtp")->name("_accountDeleteSendOtp");
        Route::post("/delete-account-verify", "accountDeleteVerify")->name("_accountDeleteVerify");
    });

    foreach (LoanServiceController::SERVICES as $service_type => $service) {
        Route::controller(LoanServiceController::class)
            ->prefix($service["slug"])
            ->group(function () use ($service_type) {
                $n = fn(string $name) => "_" . $service_type . "Service" . $name;

                Route::get("/", "landingIndex")->defaults("type", $service_type)->name($n("Index"));
                Route::get("/start", "startIndex")->defaults("type", $service_type)->name($n("Start"));

                Route::any("/verify", "stepVerifyIndex")->defaults("type", $service_type)->name($n("Verify"));
                Route::post("/send-otp", "sendOtpIndex")->middleware("throttle:10,1")->defaults("type", $service_type)->name($n("SendOtp"));
                Route::any("/profile", "stepProfileIndex")->defaults("type", $service_type)->name($n("Profile"));
                Route::any("/employment", "stepEmploymentIndex")->defaults("type", $service_type)->name($n("Employment"));
                Route::any("/eligibility", "stepEligibilityIndex")->defaults("type", $service_type)->name($n("Eligibility"));
                Route::any("/offer", "stepOfferIndex")->defaults("type", $service_type)->name($n("Offer"));
                Route::any("/login-type", "stepLoginTypeIndex")->defaults("type", $service_type)->name($n("LoginType"));

                Route::any("/payment", "stepPaymentIndex")->defaults("type", $service_type)->name($n("Payment"));
                Route::post("/payment-verify", "paymentVerifyIndex")->defaults("type", $service_type)->name($n("PaymentVerify"));

                Route::any("/banks", "stepBanksIndex")->defaults("type", $service_type)->name($n("Banks"));
                Route::get("/success", "successIndex")->defaults("type", $service_type)->name($n("Success"));
                Route::get("/failed", "failedIndex")->defaults("type", $service_type)->name($n("Failed"));
            });
    }

    Route::controller(UserPanelController::class)->group(function () {
        Route::any("/login", "loginIndex")->name("_userLogin");
        Route::post("/login/send-otp", "loginSendOtp")->middleware("throttle:10,1")->name("_userLoginSendOtp");
        Route::get("/logout", "logoutIndex")->name("_userLogout");

        Route::prefix("user")->group(function () {
            Route::get("/", fn () => redirect()->route("_userDashboard"));
            Route::get("/dashboard", "dashboardIndex")->name("_userDashboard");
            Route::get("/applications", "applicationsIndex")->name("_userApplications");
            Route::get("/applications/{application}", "applicationShow")->where("application", "[A-Za-z0-9-]+")->name("_userApplicationShow");
            Route::any("/documents/{application?}", "documentsIndex")->where("application", "[A-Za-z0-9-]+")->name("_userDocuments");
            Route::any("/profile", "profileIndex")->name("_userProfile");
            Route::any("/support", "supportIndex")->name("_userSupport");
            Route::any("/notifications", "notificationsIndex")->name("_userNotifications");
            Route::get("/transactions", "transactionsIndex")->name("_userTransactions");
            Route::any("/delete-account", "deleteAccountIndex")->name("_userDeleteAccount");
        });
    });
});

/*
Route::fallback(function () {
    return redirect()->route("_index404");
}); */
