<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            ####################### Lead Type ##########################
            $lead_type = [
                "Personal Loan",
                "Business Loan",
                "Home Loan",
                "Car Loan",
                "Mortgage Loan",
                "Machine Loan",
                "Loan Against Property",
                "Project Loan",
                "Commercial Vehicle Loan",
                "Other Loan",
            ];
            foreach ($lead_type as $k => $v) {
                DB::table("lead_type")->insert([
                    "uuid" => strtolower(str::uuid()->toString()),
                    "label" => $v
                ]);
            }

            ####################### Lead Amount ##########################
            $lead_amount = [
                "1 Lakh - 5 Lakh",
                "5 Lakh - 10 Lakh",
                "10 Lakh - 20 Lakh",
                "20 Lakh - 50 Lakh",
                "Above 50 Lakh",
            ];
            foreach ($lead_amount as $k => $v) {
                DB::table("lead_amount")->insert([
                    "uuid" => strtolower(str::uuid()->toString()),
                    "label" => $v
                ]);
            }


            ####################### Offer Lead Type ##########################
            $offer_lead_type = [
                [
                    "label" => "Instant Offer",
                    "heading" => "Instant Offer",
                    "slug" => "instant",
                    "price" => 999.00,
                    "s_price" => 499.00,
                ],
                [
                    "label" => "NEO Card",
                    "heading" => "NEO Card",
                    "slug" => "neo-card",
                    "price" => 999.00,
                    "s_price" => 499.00,
                ],
                [
                    "label" => "LEO card",
                    "heading" => "LEO card",
                    "slug" => "leo-card",
                    "price" => 999.00,
                    "s_price" => 499.00,
                ]
            ];
            foreach ($offer_lead_type as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("offer_lead_type")->insert($v);
            }

            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
