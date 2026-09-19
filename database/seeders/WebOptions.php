<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class WebOptions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $web_options = [
            [
                "op_group" => "google",
                "op_key" => "google_domain_verification",
                "op_label" => "google domain verification",
                "op_value" => "pp-d4jItwv0CHqaC9k8vCGMndoYL8gWU79aMblwtS3Y",
            ],
            [
                "op_group" => "google",
                "op_key" => "google_analytics",
                "op_label" => "google analytics",
                "op_value" => "G-MB75XXCVM1",
            ],
            [
                "op_group" => "google",
                "op_key" => "google_tag_manager",
                "op_label" => "google tag manager",
                "op_value" => "GTM-KJ9HMRVF",
            ],
            [
                "op_group" => "pinterest",
                "op_key" => "pinterest_domain_verification",
                "op_label" => "Pinterest Domain Verification",
                "op_value" => "cd45cbfdf147cd9c74ae40601cfc4b7a",
            ],
            [
                "op_group" => "2factor",
                "op_key" => "twofactor_otp_sms_auth_key",
                "op_label" => "2factor OTP SMS Auth Key",
                "op_value" => "6fcd1786-ce76-11ef-8b17-0200cd936042",
            ],
            [
                "op_group" => "facebook",
                "op_key" => "facebook_domain_verification",
                "op_label" => "Facebook Domain Verification",
                "op_value" => "vu3riagfem8h226x2hengigylh7dqo",
            ],
            [
                "op_group" => "facebook",
                "op_key" => "facebook_pixel_key",
                "op_label" => "Facebook Pixel Key",
                "op_value" => "482726981536252",
            ],
            [
                "op_group" => "facebook",
                "op_key" => "facebook_event_name",
                "op_label" => "Facebook Event Name",
                "op_value" => "Purchase",
            ],
            [
                "op_group" => "facebook",
                "op_key" => "facebook_event_id",
                "op_label" => "Facebook Event Id",
                "op_value" => "1673613686888383",
            ],
            [
                "op_group" => "facebook",
                "op_key" => "facebook_access_token",
                "op_label" => "Facebook Access Token",
                "op_value" => "EAAsYtLYKZA34BO1HQqrlLpBa4dSPe8VknRdb51TxbekiIeFZA5yC21IfO4tyaJZAJiUtfi9fiqRtqfI9LLBZAuzWNdgDwcThWZBycmv7JZBt3pUJBmPYau7ZCQeZAM6DlRzseLYX6uDoW16RjZCxBfhzquXd9eO9hFEDZAGiAMOOjOoU208DMQWi1e1WxWwIlcBG2o5gZDZD",
            ],
        ];

        $message_configuration = [
            [
                "op_group" => "",
                "op_key" => "pl_offer_sms",
                "op_label" => "pl offer sms",
                "op_value" => "Your Loan Offer Rs.5,35,000/- is Successfully Pre-Approved. Get Disbursal in Your Bank A/C Just 10 Mins. Apply https://#.com/digital/personalLoan.",
            ],
            [
                "op_group" => "",
                "op_key" => "pl_remarketing_sms",
                "op_label" => "pl remarketing sms",
                "op_value" => "Your Loan Offer Rs.500000 is Successfully Pre-Approved. Get Disbursal in Your Bank A/C Just 10 Mins. Apply https://#.com/loan/business",
            ],
        ];

        try {
            DB::beginTransaction();
            foreach ($web_options as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("web_options")->insert($v);
            }

            foreach ($message_configuration as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("message_configuration")->insert($v);
            }
            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
