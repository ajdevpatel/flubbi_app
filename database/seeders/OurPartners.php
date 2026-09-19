<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OurPartners extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $our_partners = [
            [
                "label" => "Axis Bank",
                "logo" => "001.png",
                "priority" => 1,
            ],
            [
                "label" => "Yes Bank",
                "logo" => "002.png",
                "priority" => 2,
            ],
            [
                "label" => "ICICI Bank",
                "logo" => "003.png",
                "priority" => 3,
            ],
            [
                "label" => "Kotak Mahindra Bank",
                "logo" => "004.png",
                "priority" => 4,
            ],
            [
                "label" => "HDFC Bank",
                "logo" => "005.png",
                "priority" => 5,
            ],
            [
                "label" => "TATA Capital",
                "logo" => "006.png",
                "priority" => 6,
            ],
            [
                "label" => "Indusind Bank",
                "logo" => "007.png",
                "priority" => 7,
            ],
            [
                "label" => "SBI Bank",
                "logo" => "008.png",
                "priority" => 8,
            ],
            [
                "label" => "IDBI Bank",
                "logo" => "009.png",
                "priority" => 9,
            ],
            [
                "label" => "Bandhan Bank",
                "logo" => "010.png",
                "priority" => 10,
            ],
            [
                "label" => "Union Bank",
                "logo" => "011.png",
                "priority" => 11,
            ],
            [
                "label" => "RBL Bank",
                "logo" => "012.png",
                "priority" => 12,
            ],
            [
                "label" => "Aditya Birla Capital",
                "logo" => "013.png",
                "priority" => 13,
            ],
            [
                "label" => "Indiabulls",
                "logo" => "014.png",
                "priority" => 14,
            ],
            [
                "label" => "Faircent.com",
                "logo" => "015.png",
                "priority" => 15,
            ],
            [
                "label" => "Fullertor India",
                "logo" => "016.png",
                "priority" => 16,
            ],
            [
                "label" => "DCB Bank",
                "logo" => "017.png",
                "priority" => 17,
            ],
            [
                "label" => "Grihashakti",
                "logo" => "018.png",
                "priority" => 18,
            ],
            [
                "label" => "PaySense",
                "logo" => "019.png",
                "priority" => 19,
            ],
            [
                "label" => "Lendingkart",
                "logo" => "023.png",
                "priority" => 20,
            ],
            [
                "label" => "Indifi",
                "logo" => "021.png",
                "priority" => 21,
            ],
            [
                "label" => "Money View",
                "logo" => "022.png",
                "priority" => 22,
            ],
            [
                "label" => "Other Bank",
                "logo" => "099.jpg",
                "priority" => 23,
            ],
            [
                "label" => "Moneytap",
                "logo" => "024.png",
                "priority" => 24,
            ],
            [
                "label" => "IDFC First Bank",
                "logo" => "025.png",
                "priority" => 25,
            ],
            [
                "label" => "Bajaj Finserv",
                "logo" => "026.png",
                "priority" => 26,
            ],
            [
                "label" => "Ziploan",
                "logo" => "028.png",
                "priority" => 27,
            ],
            [
                "label" => "Credit Enable",
                "logo" => "029.png",
                "priority" => 28,
            ],
            [
                "label" => "Hero Fincorp",
                "logo" => "030.png",
                "priority" => 29,
            ],
            [
                "label" => "Monexo",
                "logo" => "031.png",
                "priority" => 30,
            ],
            [
                "label" => "NeoGrowth",
                "logo" => "032.png",
                "priority" => 31,
            ],
        ];

        try {
            DB::beginTransaction();
            foreach ($our_partners as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("our_partners")->insert($v);
            }
            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
