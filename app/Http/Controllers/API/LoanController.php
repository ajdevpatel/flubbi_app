<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;


class LoanController extends Controller
{

    #######################################################################

    public function payment_data(string $type = "self")
    {
        if ("self" == $type) {
            $data = [
                "payment_type" => "razorpay",
                "base_amount" => 0,
                "gst_rate" => 0,
                "gst_amount" => 0,
                "total_amount" => 0,
            ];
        } else {
            $data = [
                "payment_type" => "razorpay",
                "base_amount" => 499,
                "gst_rate" => 18,
                "gst_amount" => 89.82,
                "total_amount" => 588.82,
            ];
        }
        return $data;
    }

    public function loanPurposes()
    {
        $purposes = DB::table('loan_purposes')
            ->select('id', 'label')
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Loan purposes fetched successfully',
            'data'    => $purposes
        ], 200);
    }

    public function loanTypes()
    {
        $types = DB::table('loan_types')
            ->select(
                'id',
                'label',
                'rate',
                'payment'
            )
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Loan types fetched successfully',
            'data' => $types
        ], 200);
    }

    public function bankList()
    {
        $banks = DB::table('banks')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->select(
                'id',
                'name',
                'pl_rate',
                'bl_rate',
                DB::raw("CONCAT('" . url('/') . "/', logo) as logo")
            )
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $banks
        ], 200);
    }

    public function cibilScoreList()
    {
        $scores = DB::table('cibil_scores')
            ->select('id', 'label')
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'CIBIL score list fetched successfully',
            'data' => $scores
        ]);
    }

    public function selfLoginBanksList(Request $request)
    {
        $query = DB::table('self_login_banks')
            ->select([
                'self_login_banks.*',
                DB::raw("CONCAT('" . config("app.url") . "','/banks/',self_login_banks.logo) as logo"),
            ])
            ->where('status', 1)
            ->orderBy('label', 'asc');

        $banks = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'Bank list fetched successfully',
            'data' => $banks
        ], 200);
    }


    #######################################################################

    public function userLoanList(Request $request)
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 10);

        $loans = DB::table('loan_applications as la')
            ->where('la.user_id', $user->id)
            ->whereIn('la.loan_type_id', [1, 2])
            ->leftJoin('loan_types as lt', 'la.loan_type_id', '=', 'lt.id')
            ->leftJoin('loan_status as ls', 'la.status', '=', 'ls.id')
            ->leftJoin('payment_transactions as pm', 'la.id', '=', 'pm.loan_application_id')
            ->select(
                'la.id as loan_application_id',
                'la.application_no',
                'ls.label as status_label',
                'lt.label as loan_type',
                'la.status',
                'la.step',
                'la.eligible_amount',
                'la.tenure_months',
                'la.login_type',
                'la.applied_at',
                'la.payment_status',
                'pm.status as tz_status',
            )
            ->orderBy('la.applied_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Loan applications fetched successfully',
            'data' => $loans
        ]);
    }

    public function loanDetail(Request $request, $loanId)
    {
        $user = $request->user();
        $userId = $user->id;

        $loan = DB::table('loan_applications as la')
            ->leftJoin('loan_types as lt', 'lt.id', '=', 'la.loan_type_id')
            ->leftJoin('loan_purposes as lp', 'lp.id', '=', 'la.loan_purpose_id')
            ->leftJoin('cibil_scores as cs', 'cs.id', '=', 'la.cibil_score')
            ->leftJoin('loan_status as ls', 'ls.id', '=', 'la.status')
            ->leftJoin('payment_transactions as pm', 'la.id', '=', 'pm.loan_application_id')
            ->leftJoin('users', 'users.id', '=', 'la.user_id')
            ->leftJoin('states', 'states.id', '=', 'users.state_id')
            ->leftJoin('districts', 'districts.id', '=', 'users.city_id')
            ->where('la.id', $loanId)
            ->where('la.user_id', $userId)
            ->select([
                'la.id as loan_id',
                'la.*',
                'states.name as state_name',
                'districts.name as city',
                'users.name',
                'users.phone',
                'users.email',
                'users.gender',
                'users.state_id',
                'users.pincode',
                'cs.label as cibil_label',
                'lt.label as loan_type',
                'lt.rate as interest_rate',
                'lp.label as loan_purpose',
                'ls.label as loan_status',
                'pm.status as tz_status',
                'pm.id as tz_id',
            ])
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Loan application not found'
            ], 404);
        }

        $self_bank =  DB::table('self_login_banks')
            ->where('id', $loan->self_login_bank_id)
            ->first();

        $bank_log = $self_bank->logo ?? "";
        $loan->bank_url = $self_bank->pl_link ?? "";
        $loan->bank_name = $self_bank->label ?? "";
        $loan->bank_logo = config("app.url") . "/banks/" . $bank_log;
        if ($loan->loan_type_id == 2) {
            $loan->bank_url = $self_bank->bl_link ?? "";
        }

        foreach ($loan as $k => $v) {
            if (in_array($k, ["aadhar_front", "aadhar_back", "pan_front", "selfie"]) && !empty($v)) {
                $loan->$k = config("app.url") . "/uploads/" . $v;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $loan
        ]);
    }

    public function loanDocuments(Request $request, $loanId)
    {
        $user = $request->user();
        $userId = $user->id;

        $loan = DB::table('loan_applications as a')
            ->leftJoin('loan_documents as d', 'd.loan_application_id', '=', 'a.id')
            ->where('a.id', $loanId)
            ->where('a.user_id', $userId)
            ->select([
                'a.aadhar_number',
                'a.aadhar_front',
                'a.aadhar_back',
                'a.pan_number',
                'a.pan_front',
                'a.selfie',
                'd.salary_slip',
                'd.bank_statement',
                'd.business_proof',
                'd.gst_return',
                'd.income_tax_eturn',
                'd.utility_bill',
                'd.cancel_cheque',
                'd.other_one',
                'd.other_two',
                'd.other_three',
                'd.other_four',
                'd.other_five'
            ])
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Loan application not found'
            ], 404);
        }

        foreach ($loan as $k => $v) {
            if (!empty($v)) {
                $loan->$k = config("app.url") . "/uploads/" . $v;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $loan
        ]);
    }

    public function uploadDocument(Request $request)
    {
        $doc_list = [
            'aadhar_front',
            'aadhar_back',
            'pan_front',
            'selfie',
            'salary_slip',
            'bank_statement',
            'business_proof',
            'gst_return',
            'income_tax_eturn',
            'utility_bill',
            'cancel_cheque',
            'other_one',
            'other_two',
            'other_three',
            'other_four',
            'other_five'
        ];

        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'doc_type' => 'required|string|in:' . implode(",", $doc_list),
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        if ($request->doc_type == "pan_front") {
            $validator = Validator::make($request->all(), [
                'pan_number' => 'required|alpha_num',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }
        }

        if ($request->doc_type == "aadhar_front" || $request->doc_type == "aadhar_back") {
            $validator = Validator::make($request->all(), [
                'aadhar_number' => 'required|alpha_num',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            #->where('status', 1)
            #->where('step', '>=', 2)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Loan not eligible for document upload'
            ], 400);
        }

        $docRow = DB::table('loan_documents')
            ->where('loan_application_id', $loan->id)
            ->first();
        if (!$docRow) {
            $docId = DB::table('loan_documents')->insertGetId([
                'loan_application_id' => $loan->id,
                'user_id' => $user->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $docId = $docRow->id;
        }

        $extension = $request->file('file')->getClientOriginalExtension();
        $random = rand(100000, 999999);

        $fileName = $loan->id . '_' . $request->doc_type . '_' . $random . '.' . $extension;
        $uploadPath = public_path('uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $request->file('file')->move($uploadPath, $fileName);

        if (in_array($request->doc_type, [
            'aadhar_front',
            'aadhar_back',
            'pan_front',
        ])) {
            $updateData = [
                $request->doc_type => $fileName,
            ];
            if ($request->doc_type == "pan_front") {
                $updateData["pan_number"] = $request->pan_number;
            } else {
                $updateData["aadhar_number"] = $request->aadhar_number;
            }
            DB::table('loan_applications')->where('id', $loan->id)->update($updateData);
        } else {
            $updateData = [
                $request->doc_type => $fileName,
                'status' => 'pending',
                'rejection_reason' => null,
                'updated_at' => now(),
            ];
            DB::table('loan_documents')->where('id', $docId ?? 0)->update($updateData);
        }

        return response()->json([
            'status' => true,
            'message' => ucfirst(str_replace('_', ' ', $request->doc_type)) . ' uploaded successfully',
            'file_name' => $fileName
        ]);
    }

    #######################################################################

    public function applyStepOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_type_id' => 'required|exists:loan_types,id',
            'employment_type' => 'required|in:salaried,self_employed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();
        $applicationNo = 'LN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $existingLoan = DB::table('loan_applications')
            ->where('user_id', $user->id)
            ->where('loan_type_id', $request->loan_type_id)
            ->where('status', 1)
            ->first();

        if ($existingLoan) {
            $x = DB::table('loan_applications')->where("id", $existingLoan->id)->update([
                'loan_type_id' => $request->loan_type_id,
                'employment_type' => $request->employment_type,
                'updated_at' => now(),
            ]);

            $loanId = $existingLoan->id;
            $applicationNo = $existingLoan->application_no;
        } else {
            $loanId = DB::table('loan_applications')->insertGetId([
                'user_id' => $user->id,
                'loan_type_id' => $request->loan_type_id,
                'employment_type' => $request->employment_type,
                'status' => 1, // Pending
                'step' => 1,
                'application_no' => $applicationNo,
                'applied_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $last_data = [];
        $userId = $user->id;

        $loan = DB::table('loan_applications as la')
            ->leftJoin('loan_types as lt', 'lt.id', '=', 'la.loan_type_id')
            ->leftJoin('loan_purposes as lp', 'lp.id', '=', 'la.loan_purpose_id')
            ->leftJoin('cibil_scores as cs', 'cs.id', '=', 'la.cibil_score')
            ->leftJoin('loan_status as ls', 'ls.id', '=', 'la.status')
            ->leftJoin('payment_transactions as pm', 'la.id', '=', 'pm.loan_application_id')
            ->leftJoin('users', 'users.id', '=', 'la.user_id')
            ->leftJoin('states', 'states.id', '=', 'users.state_id')
            ->leftJoin('districts', 'districts.id', '=', 'users.city_id')
            ->where('la.id', $loanId)
            ->where('la.user_id', $userId)
            ->select([
                'la.id as loan_id',
                'la.*',
                'states.name as state_name',
                'districts.name as city',
                'users.name',
                'users.phone',
                'users.email',
                'users.gender',
                'users.state_id',
                'users.pincode',
                'cs.label as cibil_label',
                'lt.label as loan_type',
                'lt.rate as interest_rate',
                'lp.label as loan_purpose',
                'ls.label as loan_status',
                'pm.status as tz_status',
                'pm.id as tz_id',
            ])
            ->first();

        if ($loan) {
            $last_data = $loan;
        }


        return response()->json([
            'status' => true,
            'message' => 'Loan 1st step completed successfully',
            'loan_application_id' => $loanId,
            'application_no' => $applicationNo,
            'employment_type' => $request->employment_type,
            'last_data' => $last_data,
        ], 200);
    }

    public function applyStepTwo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'loan_purpose_id' => 'required|exists:loan_purposes,id',
            'monthly_income' => 'required|numeric|min:0',
            'existing_emi' => 'required|numeric|min:0',
            'cibil_score' => 'required|exists:cibil_scores,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();

        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid loan or Step-1 not completed'
            ], 400);
        }

        $loan_type = DB::table('loan_types')
            ->where('id', $loan->loan_type_id ?? 0)
            ->first();

        if (!$loan_type) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid loan type'
            ], 400);
        }

        $step = ($loan->step == 1) ? 2 : $loan->step;

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'loan_purpose_id'  => $request->loan_purpose_id,
                'monthly_income'   => $request->monthly_income,
                'existing_emi'     => $request->existing_emi ?? 0,
                'cibil_score'      => $request->cibil_score,
                'step'             => $step,
                'updated_at'       => now(),
            ]);

        $loan_data = [
            "interest_rate" => $loan_type->rate ?? 12.50,
            "min_amount" => 50000,
            "max_amount" => $this->checkUserLoanAmountEligiblity($request->monthly_income, $request->existing_emi, $loan_type->rate ?? 12.50, 100000),
            "emi" => [
                12,
                24,
                36,
                48,
                60,
                72,
            ],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Loan Step completed successfully',
            'data' => $loan_data,
            'employment_type' => $loan->employment_type,
            'loan_application_id' => $loan->id,
        ], 200);
    }

    public function checkUserLoanAmountEligiblity($income = 10000, $emi = 1000, $apr = 12, $loanamount = 100000)
    {
        $remainamount = floor(($income * 0.40) - $emi);
        $monthlyemi = floor(($loanamount + ($loanamount * ($apr / 100)) * 6) / 72);
        $amount = floor(($loanamount * $remainamount) / $monthlyemi);
        if ($amount < 200000) {
            $amount = 195000;
        } else if ($amount > 850000) {
            $amount = 875000;
        }
        return round($amount);
    }

    public function applyStepThree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'loan_amount' => 'required|numeric|min:10000',
            'loan_emi' => 'required|numeric|between:10,200',
            'interest_rate' => 'required|numeric|between:1,99',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();

        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid loan or Step-1 not completed'
            ], 400);
        }

        $step = ($loan->step == 2) ? 3 : $loan->step;

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'eligible_amount' => $request->loan_amount,
                'tenure_months' => $request->loan_emi,
                'interest_rate' => $request->interest_rate,
                'step' => $step,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Loan Step completed successfully',
            'employment_type' => $loan->employment_type,
            'loan_application_id' => $loan->id,
        ], 200);
    }

    public function applyStepFour(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'aadhaar_number' => 'required|numeric',
            'pan_number' => 'required|alpha_num',
            'address' => 'required',
            'dob' => ['required', 'date', function ($attribute, $value, $fail) {
                $age = Carbon::parse($value)->age;
                if ($age < 18) {
                    $fail('You must be at least 18 years old.');
                }
                if (Carbon::parse($value)->isFuture()) {
                    $fail('Date of birth cannot be a future date.');
                }
            }],
            'aadhar_front' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'aadhar_back' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'pan_front' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'selfie' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid loan or Step-1 not completed'
            ], 400);
        }

        $documents = ['aadhar_front', 'aadhar_back', 'pan_front', 'selfie'];
        $uploadedFiles = [];

        foreach ($documents as $docType) {
            if ($request->hasFile($docType)) {
                $file = $request->file($docType);
                $extension = $file->getClientOriginalExtension();
                $random = rand(100000, 999999);
                $fileName = $loan->id . '_' . $docType . '_' . $random . '.' . $extension;

                $uploadPath = public_path('uploads/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $fileName);
                $uploadedFiles[$docType] = $fileName;
            }
        }

        $step = ($loan->step == 3) ? 4 : $loan->step;

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'aadhar_number' => $request->aadhaar_number,
                'pan_number' => $request->pan_number,
                'address' => $request->address,
                'dob' => $request->dob,
                'aadhar_front' => $uploadedFiles['aadhar_front'] ?? null,
                'aadhar_back' => $uploadedFiles['aadhar_back'] ?? null,
                'pan_front' => $uploadedFiles['pan_front'] ?? null,
                'selfie' => $uploadedFiles['selfie'] ?? null,
                'step' => $step,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Loan Step completed successfully',
            'employment_type' => $loan->employment_type,
            'loan_application_id' => $loan->id,
        ], 200);
    }

    public function applyStepFive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid loan or Step-1 not completed'
            ], 400);
        }

        $step = ($loan->step == 4) ? 5 : $loan->step;

        if ($loan->employment_type == "self_employed") {
            $validator = Validator::make($request->all(), [
                'business_type' => 'required|in:manufacture,trader,service',
                'business_age' => 'required|numeric|min:0|max:50',
                'business_identity_proof' => 'required|in:gst,msme,trade_license,other',
                'bank_account' => 'required|in:current_account,saving_account',
                'annual_business_turnover' => 'required|in:1,2,3',
                'marital_status' => 'required|in:married,unmarried',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            DB::table('loan_applications')
                ->where('id', $loan->id)
                ->update([
                    'business_type' => $request->business_type,
                    'business_age' => $request->business_age,
                    'business_identity_proof' => $request->business_identity_proof,
                    'bank_account' => $request->bank_account,
                    'annual_business_turnover' => $request->annual_business_turnover,
                    'marital_status' => $request->marital_status,
                    'step' => $step,
                    'updated_at' => now(),
                ]);
        } else {
            $validator = Validator::make($request->all(), [
                'job_type' => 'required|in:government,private',
                'work_experience' => 'required|numeric|min:0|max:50',
                'marital_status' => 'required|in:married,unmarried',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            DB::table('loan_applications')
                ->where('id', $loan->id)
                ->update([
                    'job_type' => $request->job_type,
                    'work_experience' => $request->work_experience,
                    'marital_status' => $request->marital_status,
                    'step' => $step,
                    'updated_at' => now(),
                ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Loan Step completed successfully',
            'loan_application_id' => $loan->id,
        ], 200);
    }

    public function applyStepSix(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'credit_card_usage'   => 'required|in:0,1',
            'bank_name'      => 'required|string|max:100',
            'bank_branch'    => 'required|string|max:100',
            'ifsc_code'      => 'required|string',
            'account_number' => 'required|string|min:9|max:30',
            /* 'ifsc_code'      => [
                'required',
                'string',
                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'
            ], */
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid loan or Step-1 not completed'
            ], 400);
        }

        $step = ($loan->step == 5) ? 6 : $loan->step;

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'credit_card_usage' => $request->credit_card_usage,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'ifsc_code' => strtoupper($request->ifsc_code),
                'account_number' => $request->account_number,
                'step' => $step,
                'updated_at' => now(),
            ]);

        $type_video = collect(DB::table('options')->whereIn('key', ['consultant_video', 'self_video'])->get());

        return response()->json([
            'status' => true,
            'message' => 'Loan Step completed successfully',
            'employment_type' => $loan->employment_type,
            'loan_application_id' => $loan->id,
            'video' => $type_video,
        ], 200);
    }

    public function applyStepSeven(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'login_type'   => 'required|in:self,consultant',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Application not found'
            ], 400);
        }

        $step = ($loan->step == 6) ? 7 : $loan->step;

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'login_type' => $request->login_type,
                'step' => $step,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Loan Step completed successfully',
            'employment_type' => $loan->employment_type,
            'loan_application_id' => $loan->id,
            'payment_data' => $this->payment_data($request->login_type),
        ], 200);
    }

    public function saveSelfLoginBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'bank_id' => 'required|exists:self_login_banks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('login_type', 'self')
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Application not found'
            ], 200);
        }

        if ($loan->payment_status != 1 || $loan->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Your payment was not successful. Please try again after making the first payment.'
            ], 200);
        }

        if (!empty($loan->self_login_bank_id)) {
            return response()->json([
                'status' => false,
                'message' => 'You have already selected a bank.'
            ], 200);
        }

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'self_login_bank_id' => $request->bank_id,
                'updated_at' => now(),
            ]);

        $self_bank =  DB::table('self_login_banks')
            ->where('id', $request->bank_id)
            ->first();

        $url = $self_bank->pl_link ?? "";
        if ($loan->loan_type_id == "2") {
            $url = $self_bank->bl_link ?? "";
        }

        return response()->json([
            'status' => true,
            'message' => 'Loan Step completed successfully',
            'loan_application_id' => $loan->id,
            'link' => $url,
        ], 200);
    }








    ######################################################################################################

    public function cardStepOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_type_id' => 'required|exists:loan_types,id|in:3',
            'employment_type' => 'required|in:salaried,self_employed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();
        $applicationNo = 'LN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $existingLoan = DB::table('loan_applications')
            ->where('user_id', $user->id)
            ->where('loan_type_id', $request->loan_type_id)
            ->where('status', 1)
            ->first();

        if ($existingLoan) {
            $x = DB::table('loan_applications')->where("id", $existingLoan->id)->update([
                'loan_type_id' => $request->loan_type_id,
                'employment_type' => $request->employment_type,
                'updated_at' => now(),
            ]);

            $loanId = $existingLoan->id;
            $applicationNo = $existingLoan->application_no;
        } else {
            $loanId = DB::table('loan_applications')->insertGetId([
                'user_id' => $user->id,
                'loan_type_id' => $request->loan_type_id,
                'employment_type' => $request->employment_type,
                'status' => 1, // Pending
                'step' => 1,
                'application_no' => $applicationNo,
                'applied_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Card 1st step completed successfully',
            'loan_application_id' => $loanId,
            'application_no' => $applicationNo,
            'employment_type' => $request->employment_type,
        ], 200);
    }

    public function cardStepTwo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'monthly_income' => 'required|numeric|min:0',
            'existing_emi' => 'required|numeric|min:0',
            'cibil_score' => 'required|exists:cibil_scores,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = $request->user();

        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Card or Step-1 not completed'
            ], 400);
        }

        $loan_type = DB::table('loan_types')
            ->where('id', $loan->loan_type_id ?? 0)
            ->first();

        if (!$loan_type) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Card type'
            ], 400);
        }

        $step = ($loan->step == 1) ? 2 : $loan->step;

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'monthly_income'   => $request->monthly_income,
                'existing_emi'     => $request->existing_emi ?? 0,
                'cibil_score'      => $request->cibil_score,
                'step'             => $step,
                'updated_at'       => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Card Step completed successfully',
            'employment_type' => $loan->employment_type,
            'loan_application_id' => $loan->id,
        ], 200);
    }

    public function cardStepThree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
            'aadhaar_number' => 'required|numeric',
            'pan_number' => 'required|alpha_num',
            'address' => 'required',
            'dob' => ['required', 'date', function ($attribute, $value, $fail) {
                $age = Carbon::parse($value)->age;
                if ($age < 18) {
                    $fail('You must be at least 18 years old.');
                }
                if (Carbon::parse($value)->isFuture()) {
                    $fail('Date of birth cannot be a future date.');
                }
            }],
            'aadhar_front' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'aadhar_back' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'pan_front' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'selfie' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Card or Step-1 not completed'
            ], 400);
        }

        $documents = ['aadhar_front', 'aadhar_back', 'pan_front', 'selfie'];
        $uploadedFiles = [];

        foreach ($documents as $docType) {
            if ($request->hasFile($docType)) {
                $file = $request->file($docType);
                $extension = $file->getClientOriginalExtension();
                $random = rand(100000, 999999);
                $fileName = $loan->id . '_' . $docType . '_' . $random . '.' . $extension;

                $uploadPath = public_path('uploads/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $fileName);
                $uploadedFiles[$docType] = $fileName;
            }
        }

        $step = ($loan->step == 2) ? 3 : $loan->step;

        DB::table('loan_applications')
            ->where('id', $loan->id)
            ->update([
                'aadhar_number' => $request->aadhaar_number,
                'pan_number' => $request->pan_number,
                'address' => $request->Address,
                'dob' => $request->dob,
                'aadhar_front' => $uploadedFiles['aadhar_front'] ?? null,
                'aadhar_back' => $uploadedFiles['aadhar_back'] ?? null,
                'pan_front' => $uploadedFiles['pan_front'] ?? null,
                'selfie' => $uploadedFiles['selfie'] ?? null,
                'step' => $step,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Card Step completed successfully',
            'employment_type' => $loan->employment_type,
            'loan_application_id' => $loan->id,
        ], 200);
    }

    public function cardStepFour(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_application_id' => 'required|exists:loan_applications,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = $request->user();
        $loan = DB::table('loan_applications')
            ->where('id', $request->loan_application_id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Card or Step-1 not completed'
            ], 400);
        }

        if ($loan->employment_type == "self_employed") {
            $validator = Validator::make($request->all(), [
                'business_type' => 'required|in:manufacture,trader,service',
                'business_age' => 'required|numeric|min:0|max:50',
                'business_identity_proof' => 'required|in:gst,msme,trade_license,other',
                'bank_account' => 'required|in:current_account,saving_account',
                'annual_business_turnover' => 'required|in:1,2,3',
                'marital_status' => 'required|in:married,unmarried',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $step = ($loan->step == 3) ? 4 : $loan->step;

            DB::table('loan_applications')
                ->where('id', $loan->id)
                ->update([
                    'business_type' => $request->business_type,
                    'business_age' => $request->business_age,
                    'business_identity_proof' => $request->business_identity_proof,
                    'bank_account' => $request->bank_account,
                    'annual_business_turnover' => $request->annual_business_turnover,
                    'marital_status' => $request->marital_status,
                    'step' => 4,
                    'status' => 2,
                    'updated_at' => now(),
                ]);
        } else {
            $validator = Validator::make($request->all(), [
                'job_type' => 'required|in:government,private',
                'work_experience' => 'required|numeric|min:0|max:50',
                'marital_status' => 'required|in:married,unmarried',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            DB::table('loan_applications')
                ->where('id', $loan->id)
                ->update([
                    'job_type' => $request->job_type,
                    'work_experience' => $request->work_experience,
                    'marital_status' => $request->marital_status,
                    'step' => 4,
                    'status' => 2,
                    'updated_at' => now(),
                ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Card Step completed successfully',
            'loan_application_id' => $loan->id,
        ], 200);
    }

    public function userCardList(Request $request)
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 10);

        $loans = DB::table('loan_applications as la')
            ->where('la.user_id', $user->id)
            ->where('la.loan_type_id', 3)
            ->leftJoin('cibil_scores as cs', 'cs.id', '=', 'la.cibil_score')
            ->leftJoin('loan_status as ls', 'ls.id', '=', 'la.status')
            ->select(
                'la.id as loan_application_id',
                'la.application_no',
                'la.employment_type',
                'la.monthly_income',
                'la.existing_emi',
                'la.status',
                'la.step',
                'la.applied_at',
                'cs.label as cibil_label',
                'ls.label as loan_status',
            )
            ->orderBy('la.applied_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Card applications fetched successfully',
            'data' => $loans
        ]);
    }
}
