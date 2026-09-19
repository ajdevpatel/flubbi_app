<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LoanController;


Route::get('states', [UserController::class, 'getStates']);
Route::post('districts', [UserController::class, 'getDistricts']);
Route::get('our-partners', [UserController::class, 'ourPartners']);
Route::get('login-video', [UserController::class, 'loginVideo']);
Route::get('support-data', [UserController::class, 'supportData']);

Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('dashboard', [UserController::class, 'dashboardData']);

    Route::get('profile', [UserController::class, 'getProfile']);
    Route::post('profile/update', [UserController::class, 'updateProfile']);
    Route::post('profile/image', [UserController::class, 'updateProfileImage']);
    Route::post('profile/delete', [UserController::class, 'deleteAccount']);

    Route::get('support/reasons', [UserController::class, 'getSupportReasons']);
    Route::post('support/create', [UserController::class, 'createSupportTicket']);
    Route::post('support/list', [UserController::class, 'supportTicketList']);

    Route::get('notifications', [UserController::class, 'notificationsList']);
    Route::get('notifications/unread-count', [UserController::class, 'unreadCount']);
    Route::post('notifications/mark-read', [UserController::class, 'notificationsMarkRead']);
    Route::post('notifications/create', [UserController::class, 'notificationCreate']);
    Route::post('fcm-token', [UserController::class, 'saveFcmToken']);

    Route::post('transaction/add', [UserController::class, 'transactionAdd']);
    Route::get('transactions-list', [UserController::class, 'transactionsList']);



    Route::get('/loan-purposes', [LoanController::class, 'loanPurposes']);
    Route::get('/loan-types', [LoanController::class, 'loanTypes']);
    Route::get('/cibil-score', [LoanController::class, 'cibilScoreList']);

    Route::post('loan/step-one', [LoanController::class, 'applyStepOne']);
    Route::post('loan/step-two', [LoanController::class, 'applyStepTwo']);
    Route::post('loan/step-three', [LoanController::class, 'applyStepThree']);
    Route::post('loan/step-four', [LoanController::class, 'applyStepFour']);
    Route::post('loan/step-five', [LoanController::class, 'applyStepFive']);
    Route::post('loan/step-six', [LoanController::class, 'applyStepSix']);
    Route::post('loan/step-seven', [LoanController::class, 'applyStepSeven']);

    Route::get('user/loan-list', [LoanController::class, 'userLoanList']);
    Route::get('user/loan/{loanId}', [LoanController::class, 'loanDetail']);

    Route::get('loan-document/{loanId}', [LoanController::class, 'loanDocuments']);
    Route::post('upload-document', [LoanController::class, 'uploadDocument']);

    Route::post('save-self-login-bank', [LoanController::class, 'saveSelfLoginBank']);

    Route::get('/banks', [LoanController::class, 'bankList']);
    Route::get('/self-login-banks-list', [LoanController::class, 'selfLoginBanksList']);



    
    Route::post('card/step-one', [LoanController::class, 'cardStepOne']);
    Route::post('card/step-two', [LoanController::class, 'cardStepTwo']);
    Route::post('card/step-three', [LoanController::class, 'cardStepThree']);
    Route::post('card/step-four', [LoanController::class, 'cardStepFour']);
    Route::get('user/card-list', [LoanController::class, 'userCardList']);
    Route::get('user/card/{cardId}', [LoanController::class, 'loanDetail']);
    
});
