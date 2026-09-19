<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            ####################### Request Reason ##########################
            $support_reasons = [
                [
                    "label" => "Service Problem",
                    "class" => "info",
                    "priority" => 1,
                ],
                [
                    "label" => "Payment Issue",
                    "class" => "success",
                    "priority" => 1,
                ],
                [
                    "label" => "Technical Problem",
                    "class" => "danger",
                    "priority" => 2,
                ],
                [
                    "label" => "Eligibility or Pre-Approval Query",
                    "class" => "warning",
                    "priority" => 3,
                ],
                [
                    "label" => "Other",
                    "class" => "info",
                    "priority" => 4,
                ],
            ];
            foreach ($support_reasons as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("support_reasons")->insert($v);
            }

            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
