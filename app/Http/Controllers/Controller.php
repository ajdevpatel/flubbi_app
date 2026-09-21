<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

abstract class Controller
{

    public function currentDataTime(): string
    {
        $now = new DateTime();
        return $now->format("Y-m-d h:i:s");
    }

    public function setCookie(string $c_name = "", string $c_value = "", int $c_time = 3600)
    {
        Cookie::queue($c_name, $c_value, $c_time);
        $cookie = cookie($c_name, $c_value, $c_time);

        return response('Cookie has been set')->withCookie($cookie);
    }

    public function random_code($length = 6)
    {
        //$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $chars = "01234567890123456789";
        $code = substr(str_shuffle($chars), 0, $length);
        return $code;
    }

    public function generateUUID(): string
    {
        return strtolower(str::uuid()->toString());
    }

    public function generatePassword($chars = 10): string
    {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return strtolower(substr(str_shuffle($data), 0, $chars));
    }

    public function generateVirtualCardNumber($length = 6): int
    {
        return substr(str_shuffle("01234567890123456789" . time()), 0, $length);
    }

    public function generateCustomPaymentId(int $length = 8, string $type = "cash"): string
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz" . time();
        return $type . "_" . substr(str_shuffle($chars), 0, $length);
    }

    public function getLoginUserId(): int
    {
        return Auth::user()->id;
    }

    public function getNextInvoiceNumber(): string
    {
        $last_number = DB::table("user_invoices")->orderBy("inv_number", "desc")->value("inv_number");
        if (null === $last_number) {
            $last_number = config("web.webapp.invoice.start_number");
        }
        return sprintf("%0" . config("web.webapp.invoice.digit_limit") . "d", intval($last_number) + 1);
    }

    ##################################################################################

    public function sendMail(string $to_mail = "", array $in_data = [])
    {
        if (1 != config("web.mail.is_active")) {
            return true;
        }
        if ([] == $in_data || empty($to_mail)) {
            return false;
        }
        try {
            Mail::to($to_mail)->send(new SendMail($in_data));
            return true;
        } catch (\Exception $e) {
            #$e->getMessage();
            return false;
        }
    }

    public function sendTwoFactorOtpSMS(int $phone = 0, int $otp = 0, int $otp_id = 0)
    {
        if (app()->environment("local")) {
            return true;
        }
        if (empty($phone) || empty($otp) || empty($otp_id)) {
            return false;
        }
        $twofactor_otp_sms_auth_key = DB::table("web_options")->where("web_options.op_key", "twofactor_otp_sms_auth_key")->value("op_value");
        if (null == $twofactor_otp_sms_auth_key) {
            return false;
        }

        $api_phone = "+91" . substr($phone, -10);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://2factor.in/API/V1/" . $twofactor_otp_sms_auth_key . "/SMS/" . $api_phone . "/" . $otp . "/OTP1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return false;
        }
        $res = (array) json_decode($response);
        if (isset($res["Details"])) {
            $update_log = DB::table("otp_logs")->where("otp_logs.id", $otp_id)->update([
                "otp_logs.token" => $res["Details"],
            ]);
        }
        return true;
    }

    public function verifyTwoFactorOtpSMS(int $phone = 0, int $otp = 0)
    {
        if (app()->environment("local")) {
            return true;
        }
        if (empty($phone) || empty($otp)) {
            return false;
        }
        $twofactor_otp_sms_auth_key = DB::table("web_options")->where("web_options.op_key", "twofactor_otp_sms_auth_key")->value("op_value");
        if (null == $twofactor_otp_sms_auth_key) {
            return false;
        }

        $api_phone = "+91" . substr($phone, -10);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://2factor.in/API/V1/" . $twofactor_otp_sms_auth_key . "/SMS/VERIFY3/" . $api_phone . "/" . $otp,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return false;
        }
        $res = (array) json_decode($response);
        if (isset($res["Status"]) && "success" == strtolower($res["Status"])) {
            return true;
        }
        return false;
    }

    ##################################################################################

    public function getTableStatusHtml(string $value = "", array $data = []): string
    {
        $html = "";
        if ([] === $data) {
            return $html;
        }

        if (array_key_exists("pending", $data) && $value == $data["pending"]) {
            $html = '<span class="badge bg-label-warning"><i class="ti ti-square-rounded-check"></i> Pending </span>';
        } else if (array_key_exists("active", $data) && $value == $data["active"]) {
            $html = '<span class="badge bg-label-success"><i class="ti ti-square-rounded-check"></i> Active </span>';
        } else if (array_key_exists("deactive", $data) && $value == $data["deactive"]) {
            $html = '<span class="badge bg-label-danger"><i class="ti ti-square-rounded-x"></i> De-Active </span>';
        } else if (array_key_exists("no", $data) && $value == $data["no"]) {
            $html = '<span class="badge bg-label-danger"><i class="ti ti-square-rounded-x"></i> No </span>';
        } else if (array_key_exists("yes", $data) && $value == $data["yes"]) {
            $html = '<span class="badge bg-label-success"><i class="ti ti-square-rounded-check"></i> Yes </span>';
        } else if (array_key_exists("success", $data) && $value == $data["success"]) {
            $html = '<span class="badge bg-label-success"><i class="ti ti-square-rounded-check"></i> Success </span>';
        } else if (array_key_exists("failed", $data) && $value == $data["failed"]) {
            $html = '<span class="badge bg-label-danger"><i class="ti ti-square-rounded-x"></i> Failed </span>';
        }

        return $html;
    }

    public function getTableActionHtml(array $url = []): string
    {
        $html = "";
        if ([] === $url) {
            return $html;
        }
        if (array_key_exists("edit", $url) && "" !== $url["edit"]) {
            $html .= '<a href="' . $url['edit'] . '">
                        <button class="mx-1 btn btn-icon btn-label-primary waves-effect">
                            <span class="ti ti-edit"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("delete", $url) && "" !== $url["delete"]) {
            $html .= '<a href="' . $url['delete'] . '" class="delete_btn" title="Delete">
                        <button class="mx-1 btn btn-icon btn-label-danger waves-effect">
                            <span class="ti ti-trash-filled"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("view", $url) && "" !== $url["view"]) {
            $html .= '<a href="' . $url['view'] . '">
                        <button class="mx-1 btn btn-icon btn-label-info waves-effect">
                            <span class="ti ti-eye"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("application", $url) && "" !== $url["application"]) {
            $html .= '<a href="' . $url['application'] . '">
                        <button class="mx-1 btn btn-icon btn-label-primary waves-effect">
                            <span class="ti ti-box"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("user", $url) && "" !== $url["user"]) {
            $html .= '<a href="' . $url['user'] . '">
                        <button class="mx-1 btn btn-icon btn-label-success waves-effect">
                            <span class="ti ti-user"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("invoice", $url) && "" !== $url["invoice"]) {
            $html .= '<a href="' . $url['invoice'] . '" title="Download Invoice">
                        <button class="mx-1 btn btn-icon btn-label-warning waves-effect">
                            <span class="ti ti-file-download"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("add_user", $url) && "" !== $url["add_user"]) {
            $html .= '<a href="' . $url['add_user'] . '">
                        <button class="mx-1 btn btn-icon btn-label-info waves-effect">
                            <span class="ti ti-user-plus"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("deactive", $url) && "" !== $url["deactive"]) {
            $html .= '<a href="' . $url['deactive'] . '" title="De-Active">
                        <button class="mx-1 btn btn-icon btn-label-danger waves-effect">
                            <span class="ti ti-lock"></span>
                        </button>
                    </a>';
        }

        if (array_key_exists("active", $url) && "" !== $url["active"]) {
            $html .= '<a href="' . $url['active'] . '" title="Active">
                        <button class="mx-1 btn btn-icon btn-label-success waves-effect">
                            <span class="ti ti-lock-open"></span>
                        </button>
                    </a>';
        }

        return $html;
    }

    ##################################################################################

    public function checkUserLoanAmountEligiblity($income, $emi, $apr, $loanamount)
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

    public function emiCalculation($apr, $term, $loanamount)
    {
        $term = $term * 12;
        $apr = $apr / 1200;
        $amount = $apr * -$loanamount * pow((1 + $apr), $term) / (1 - pow((1 + $apr), $term));
        return $this->amountFormatIndia(round($amount));
    }

    public function amountFormatIndia($num)
    {
        $explrestunits = "";
        $num = preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $des = "00";

        if (count($words) <= 2) {
            $num = $words[0];
            if (count($words) >= 2) {
                $des = $words[1];
            }
            if (strlen($des) < 2) {
                $des = "$des";
            } else {
                $des = substr($des, 0, 2);
            }
        }
        if (strlen($num) > 3) {
            $lastthree = substr($num, strlen($num) - 3, strlen($num));
            $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0; $i < sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0) {
                    $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash . "." . $des; // writes the final format where $currency is the currency symbol.
    }

    ##################################################################################

    public function getRoiPackages(float $loan_eligiblity_amount = 500000, bool $is_step = false)
    {
        $roi_data = collect(DB::table("roi_packages")->select([
            "roi_packages.id as p_id",
            "roi_packages.uuid",
            "roi_packages.min_roi",
            "roi_packages.max_roi",
            "roi_packages.roi",
            "roi_packages.processing_fee",
            "roi_packages.terms_years",
            "roi_packages.terms_months",
            "roi_packages.status",
            "roi_packages.label",
            "roi_packages.description",
            "loan_types.label as loan_type_label",
            "loan_types.annual_rate as loan_type_annual_rate",
            "our_partners.label as our_partner_label",
            DB::raw("CONCAT('" . config("web.webapp.base_url") . "','store/partners/',our_partners.logo) as partner_logo"),
        ])->leftJoin("loan_types", function ($join) {
            $join->on("loan_types.id", "=", "roi_packages.type_id");
        })->leftJoin("our_partners", function ($join) {
            $join->on("our_partners.id", "=", "roi_packages.partner_id");
        })->where([
            "loan_types.status" => "0",
            "roi_packages.status" => "0",
            "our_partners.status" => "1",
            "roi_packages.deleted" => "0",
            "loan_types.deleted" => "0",
            "our_partners.deleted" => "0",
        ])->orderBy("roi_packages.roi", "asc")->get())->toArray();

        $roi_list = [];
        if ([] != $roi_data) {
            foreach ($roi_data as $k => $v) {
                $index = (array) $v;
                $index["loan_eligiblity_amount"] = "₹" . $this->amountFormatIndia($loan_eligiblity_amount) . "/-";
                $index["emi_amount"] = "₹" . $this->emiCalculation($v->roi, $v->terms_years, $loan_eligiblity_amount);
                $roi_list[] = $index;
            }
        }

        if (true == $is_step) {
            //$this->setCookie("roiPackageStepSection", "dddddddddddddddd");
        }

        return $roi_list;
    }

    public function sendUserCredentials($user_id, $password = null)
    {
        $user_data = collect(DB::table("users")->where("users.id", $user_id)->where("users.role", "2")->first())->toArray();
        if ([] == $user_data) {
            return false;
        }

        $g_password =  $this->generatePassword(8);
        if (null != $password || !empty($password)) {
            $g_password =  $password;
        }
        DB::table("users")->where("id", $user_id)->update([
            "password" => Hash::make($g_password),
            "updated_at" => $this->currentDataTime(),
        ]);

        $in_data = [
            "subject" => "Your " . config("web.webapp.env.app_name") . " account has been created!",
            "body_text" => "<p style='margin: 0 0 0.2rem 0;line-height:1;font-size:1rem;font-weight:600'>Hi " . $user_data['name'] . ",</p>
            <p style='margin:0 0 0 0.5rem;line-height:1.3;font-size:0.9rem;padding-top: 0.6rem;'>
            Your Account Information :
            </p>
            <p style='margin:0 0 0 0.5rem;line-height:1.3;font-size:0.9rem;padding-top: 0.6rem;'>
            <b> User Id : " .  $user_data['email'] . "</b>
            </p>
            <p style='margin:0 0 0 0.5rem;line-height:1.3;font-size:0.9rem;padding-top: 0.2rem;'>
            <b> Password : " . $g_password . "</b>
            </p>
            <p style='margin:0 0 0 0.5rem;line-height:1.3;font-size:0.9rem;padding-top: 0.6rem;'>
            Please don't share with anyone
            </p>",
        ];

        $this->sendMail($user_data['email'], $in_data);
    }



    ##################################################################################
    public function fbConversionCURL($data)
    {
        if (app()->environment("local")) {
            return true;
        }

        $fbaccesstoken = DB::table("web_options")->where("op_key", "facebook_access_token")->value("op_value");
        if (!$fbaccesstoken) {
            return false;
        }
        $eventname = DB::table("web_options")->where("op_key", "facebook_event_name")->value("op_value");
        if (!$eventname) {
            return false;
        }
        $eventid = DB::table("web_options")->where("op_key", "facebook_event_id")->value("op_value");
        if (!$eventid) {
            return false;
        }
        $fbpixel = DB::table("web_options")->where("op_key", "facebook_pixel_key")->value("op_value");
        if (!$fbpixel) {
            return false;
        }
        /*
{
    "data": [
        {
            "event_name": "Purchase",
            "event_time": 1737639743,
            "action_source": "website",
            "user_data": {
                "em": [
                    "7b17fb0bd173f625b58636fb796407c22b3d16fc78302d79f0fd30c2fc2fc068"
                ],
                "ph": [
                    null
                ]
            },
            "custom_data": {
                "currency": "USD",
                "value": "142.52"
            },
            "original_event_data": {
                "event_name": "Purchase",
                "event_time": 1737639743
            }
        }
    ]
}
$in_arr = [
    "data" => [
        "action_source" => "website",
        "event_source_url" => $_ENV["APP_URL"],
        "event_time" => time(),
        "event_id" => $eventid,
        "event_name" => $eventname,
        "user_data" => [
            "client_ip_address" => "",
            "client_user_agent" => "",
        ],
        "custom_data" => [
            "currency" => "INR",
            "value" => 500.00,
            "num_items" => 1,
            "content_type" => "product",
            "order_id" => $data['orderid'],
            "status" => "registered",
        ]
    ]
];
*/

        $data = $emarr = $pharr = $contents = array();
        $data["event_time"] = round(microtime(true));
        $data["event_name"] = $eventname;
        $data["event_id"] = $eventid;
        $data["event_source_url"] = $data['sourceurl'];

        $fnarr[] = hash("sha256", $data['firstname']);
        $data["user_data"]["fn"] = $fnarr;

        $emarr[] = hash("sha256", $data['email']);
        $data["user_data"]["em"] = $emarr;

        $pharr[] = hash("sha256", $data['mobile']);
        $data["user_data"]["ph"] = $pharr;

        $ctarr[] = hash("sha256", $data['city']);
        $data["user_data"]["ct"] = $ctarr;

        $statearr[] = hash("sha256", $data['state']);
        $data["user_data"]["st"] = $statearr;

        $countryarr[] = hash("sha256", "in");
        $data["user_data"]["country"] = $countryarr;

        $data["user_data"]["client_ip_address"] = $_SERVER['REMOTE_ADDR'];
        $data["user_data"]["client_user_agent"] = $_SERVER['HTTP_USER_AGENT'];

        if ($data["fbclid"] != "") {
            $data["user_data"]["fbc"] = $data["fbclid"];
        }

        $data["custom_data"]["currency"] = "INR";
        $data["custom_data"]["value"] = 500.00;
        $data["custom_data"]["num_items"] = 1;
        $data["custom_data"]["content_type"] = "product";
        $data["custom_data"]["order_id"] = $data['orderid'];
        $data["custom_data"]["status"] = "registered";

        $contents["id"] = "RB" . date("Y");
        $contents["quantity"] = 1;
        $contents["item_price"] = 500.00;
        $data["custom_data"]["contents"] = array($contents);
        $data["action_source"] = "website";

        $data_json = json_encode(array($data));
        $fields = array();
        $fields['access_token'] = $fbaccesstoken;
        $fields['upload_tag'] = "orders";
        $fields['data'] = $data_json;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            #Replace with your offline_event_set_id
            CURLOPT_URL => "https://graph.facebook.com/v22.0/" . $fbpixel . "/events?access_token=" . $fbaccesstoken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                #"content-type: multipart/form-data",
                "Accept: application/json",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    ##################################################################################
}
