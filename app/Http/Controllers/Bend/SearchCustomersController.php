<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SearchCustomersController extends Controller
{
    public function searchCustomersIndex()
    {
        return view("backend.searchCustomersIndex");
    }

    public function searchCustomerByPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10'
        ]);

        $customer = DB::table('users')
            ->select('uuid', 'name', 'phone', 'email', DB::raw("CASE WHEN status = 1 THEN 'Active' ELSE 'Inactive' END as status"))
            ->where('phone', $request->phone)
            ->where('role', 2)
            ->first();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'No customer found'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $customer
        ]);
    }
}
