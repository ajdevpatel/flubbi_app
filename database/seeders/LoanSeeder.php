<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            ####################### Loan Purpose ##########################
            $loan_purposes = [
                "Personal Use",
                "Property Renovation",
                "Wedding Expenses",
                "Education Expenses",
                "Medical Emergency",
                "Pay Off Credit Cards",
                "Major Purchase",
                "Everyday Bills",
                "Business",
                "Other",
            ];
            foreach ($loan_purposes as $k => $v) {
                DB::table("loan_purposes")->insert([
                    "uuid" => strtolower(str::uuid()->toString()),
                    "label" => $v
                ]);
            }

            ####################### Cibil Score ##########################
            $cibil_scores = [
                "Excellent (750+)",
                "Good (681-750)",
                "Poor (640 and below)",
            ];
            foreach ($cibil_scores as $k => $v) {
                DB::table("cibil_scores")->insert([
                    "uuid" => strtolower(str::uuid()->toString()),
                    "label" => $v
                ]);
            }

            ####################### Loan Status ##########################
            $loan_status = [
                [
                    "label" => "Payment Initiated/Pending",
                    "class" => "warning",
                    "priority" => 1,
                ],
                [
                    "label" => "New & Pending",
                    "class" => "info",
                    "priority" => 1,
                ],
                [
                    "label" => "In Process",
                    "class" => "warning",
                    "priority" => 1,
                ],
                [
                    "label" => "Verification",
                    "class" => "info",
                    "priority" => 1,
                ],
                [
                    "label" => "On Hold",
                    "class" => "warning",
                    "priority" => 1,
                ],
                [
                    "label" => "Query Process",
                    "class" => "warning",
                    "priority" => 1,
                ],
                [
                    "label" => "Approved",
                    "class" => "success",
                    "priority" => 1,
                ],
                [
                    "label" => "Rejected",
                    "class" => "danger",
                    "priority" => 1,
                ],
                [
                    "label" => "Disbursed",
                    "class" => "success",
                    "priority" => 1,
                ],
                [
                    "label" => "File Reopen",
                    "class" => "info",
                    "priority" => 1,
                ],
            ];
            foreach ($loan_status as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("loan_status")->insert($v);
            }

            ####################### Loan Type ##########################
            $loan_types = [
                [
                    "label" => "instant",
                    "annual_rate" => 12.5,
                    "price" => 999,
                    "s_price" => 499,
                    "is_default" => "1",
                    "card_heading" => "instant cash",
                    "inv_prefix" => "pl_",
                ],
                [
                    "label" => "personal",
                    "annual_rate" => 12.5,
                    "price" => 999,
                    "s_price" => 499,
                    "is_default" => "0",
                    "card_heading" => "personal",
                    "inv_prefix" => "pl_",
                ],
                [
                    "label" => "business",
                    "annual_rate" => 11.5,
                    "price" => 1199,
                    "s_price" => 599,
                    "is_default" => "0",
                    "card_heading" => "business",
                    "inv_prefix" => "bl_",
                ],
            ];
            foreach ($loan_types as $k => $v) {
                DB::table("loan_types")->insert(array_merge([
                    "uuid" => strtolower(str::uuid()->toString())
                ], $v));
            }

            ####################### Loan Remarks Message ##########################
            $loan_remarks_message = [
                [
                    "label" => "Verification Successful",
                    "message" => "Dear Customer, Congratulations! Your verification is successfully done. The Login Department has asked you for the required documents. Kindly submit the documents in your customer portal in the next 24 to 48 hours. Please stay in contact with the company for the next 7 working days. If you have any doubts or queries, call on +918160300254. You can call us between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 1,
                ],
                [
                    "label" => "4 day documents pending new (document warning)",
                    "message" => "Dear Customer, you've still not submitted the documents for the loan process. Kindly submit the documents in your customer portal in 24-48 hours else your file will be automatically rejected from the system â€“ and the same would be updated in your portal. For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 2,
                ],
                [
                    "label" => "4 day documents pending new (documents reject)",
                    "message" => "Dear Customer, the company has yet not received any documents or information from your side â€“ and due to this, your file has been rejected. You can reapply for a loan after 6 months. For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 3,
                ],
                [
                    "label" => "OTP Not Given (OTP warning remark)",
                    "message" => "Dear Customer, our company asked you for the OTP for your loan process but you denied to share the OTP. As per bank rules, OPT is a must for the loan process. So, if you wish to give OTP for your loan process, kindly call us on +918160300254 in the next 24-48 hours else your file will be automatically rejected from our system. You can call us between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 4,
                ],
                [
                    "label" => "OTP Not Given (OTP reject)",
                    "message" => "Dear Customer, we are sorry to inform you that your Personal Loan application in our organization has been declined because you didnâ€™t provide the required OTP for further processes. For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 5,
                ],
                [
                    "label" => "Customer not connect 3 days new (warning)",
                    "message" => "Dear Customer, our login department is trying to contact you for the loan process for the last 3 days. But you've not responded or you're not coming in contact with the company. If you're willing to go ahead with your loan process, kindly call on +918160300254 in the next 24-48 hours (between 10 AM â€“ 5 PM; between Monday and Saturday â€“ only business days); otherwise, your file will be automatically rejected from the system.",
                    "priority" => 6,
                ],
                [
                    "label" => "Customer not connect 3 days new (reject)",
                    "message" => "Dear Customer, the company's Login Department has tried contacting you regarding the loan process â€“ to which you've not responded or you're not coming in contact with us, and due to this your file has been automatically rejected from the system â€“ and the same has been updated and shown in your portal. For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 7,
                ],
                [
                    "label" => "File Reject (ABP Low, CIBIL Low, PL Inquiry)",
                    "message" => "Dear Customer, we are sorry to inform you that your application for a loan in our organization has been rejected because you do not meet the required criteria (Average Banking, PL inquiries, Obligations, CIBIL low, ABP low, etc.). For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 8,
                ],
                [
                    "label" => "Language issue (warning)",
                    "message" => "Dear Customer, our Login Department contacted you but the process couldn't proceed due to unclear or non-understandable communication/language from your end. We suggest you make a trusted person/third-party call on your behalf within the next 24-48 hours and communicate in an understandable language/manner â€“ failing in doing so would lead to automatic rejection of your file from the system. You can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 9,
                ],
                [
                    "label" => "Language Issue (reject)",
                    "message" => "Dear Customer, we are sorry to inform you that your Personal Loan application in our organization has been declined due to unclear or non-understandable communication from your end. For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 10,
                ],
                [
                    "label" => "NR Switched Off At Login Time (warning)",
                    "message" => "Dear Customer, our login department called you at the Customer Login Time but either your contact number was switched off or unreachable. If you wish to proceed with your loan process, kindly call us on +918160300254 within the next 24-48 hours else your file will be automatically rejected in the system. You can call us between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 11,
                ],
                [
                    "label" => "NR Switched Off At Login Time (reject)",
                    "message" => "Dear Customer, we are sorry to inform you that your application for a personal loan in our organization has been declined because even after several tries of reaching out, you are unreachable or your registered mobile number is switched off. For more info call on +918160300254. You can call us between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 12,
                ],
                [
                    "label" => "Loan Approval Confirmation",
                    "message" => "Dear Customer, Congratulations! Your Personal Loan of amount ____ is approved. For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 13,
                ],
                [
                    "label" => "Application Reopened",
                    "message" => "Dear Customer, you had applied to our company for a loan but as you were not in contact with our company, your file is closed - the reason could be one of the following: (1) You didn't submit your document to the company for the login process; (2) You didn't respond to our calls; (3) You didn't send any of OTP for the login process, etc. So now, as you contacted us again to reopen your file, we are re-opening your file for the loan process and after that, you have to be in contact with our company for 7 days. For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 14,
                ],
                [
                    "label" => "Customer not interested (warning)",
                    "message" => "Dear Customer, when our login department called you regarding your loan process, you expressed uninterest. If you want to take your loan process forward, call us on +918160300254 within the next 24-48 hours else your file will be automatically rejected in the system. You can call us between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 15,
                ],
                [
                    "label" => "Customer not interested (reject)",
                    "message" => "Dear Customer, we are sorry to inform you that your Loan application in our organization has been declined because of the uninterest shown by you due to any reason(s). For more info, you can call us on +918160300254 between 10 AM to 5 PM (Monday to Saturday - only business days).",
                    "priority" => 16,
                ],
            ];
            foreach ($loan_remarks_message as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("loan_remarks_message")->insert($v);
            }

            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
