<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

#$id = Auth::id();
#$user = Auth::user();
#$user = auth()->user();

class UsersController extends Controller
{

    public function dashboardIndex()
    {

        // Customer counts are based on unique users created today that match specific loan application contexts
        $allCustomersCount = DB::table('users')->where('role', '2')->whereNull('deleted_at')->whereDate('created_at', today())->distinct()->count('id');

        // Application counts are based on actual application volume applied today
        $plApplicationCountSelf = DB::table('loan_applications')->where('loan_type_id', '1')->where('login_type', 'self')->whereDate('applied_at', today())->count();
        $plApplicationCountHireAgent = DB::table('loan_applications')->where('loan_type_id', '1')->where('login_type', 'consultant')->whereDate('applied_at', today())->count();
        $blApplicationCountSelf = DB::table('loan_applications')->where('loan_type_id', '2')->where('login_type', 'self')->whereDate('applied_at', today())->count();
        $blApplicationCountHireAgent = DB::table('loan_applications')->where('loan_type_id', '2')->where('login_type', 'consultant')->whereDate('applied_at', today())->count();

        $creditCardApplicationCount = DB::table('loan_applications')->where('loan_type_id', '3')->whereDate('applied_at', today())->count();

        //$transactionCount = (DB::table('transactions')->whereDate('created_at', today())->count()) ?? 0;
        $transactionCount = 0;
        $otpCount = DB::table('otp_logs')->whereDate('created_at', today())->count();
        $supportRequestCount = DB::table('support_tickets')->where('status', 'open')->whereDate('created_at', today())->count();

        return view("backend.dashboard", compact("otpCount", "plApplicationCountSelf", "plApplicationCountHireAgent", "blApplicationCountSelf", "blApplicationCountHireAgent", "creditCardApplicationCount", "supportRequestCount", "allCustomersCount", "transactionCount"));
    }
}
