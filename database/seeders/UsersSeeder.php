<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ##################### User Role Data #######################
        $role_data = [
            [
                "label" => "super admin",
            ],
            [
                "label" => "admin",
            ],
            [
                "label" => "employee",
            ],
            [
                "label" => "customer",
            ],
        ];


        ####################### User Data ##########################
        $users_data = [
            [
                "name" => "Edigitrix",
                "email" => "dev@edigitrix.com",
                "phone" => "8866442201",
                "role" => "1",
                "password" => Hash::make(8866442200)
            ],
            [
                "name" => "Ajay Edigitrix",
                "email" => "aj@edigitrix.com",
                "phone" => "8866442202",
                "role" => "2",
                "password" => Hash::make(9876543210)
            ],
            [
                "name" => "Kishan Edigitrix",
                "email" => "kishan@edigitrix.com",
                "phone" => "8866442203",
                "role" => "2",
                "password" => Hash::make(9876543210)
            ],
            [
                "name" => "01 Edigitrix",
                "email" => "admin01@edigitrix.com",
                "phone" => "8866442204",
                "role" => "2",
                "password" => Hash::make(9876543210)
            ],
            [
                "name" => "02 Edigitrix",
                "email" => "admin02@edigitrix.com",
                "phone" => "8866442205",
                "role" => "2",
                "password" => Hash::make(9876543210)
            ],
            [
                "name" => "Dev User",
                "email" => "user@edigitrix.com",
                "phone" => "8800660044",
                "role" => "4",
                "password" => Hash::make(1234567890)
            ],
        ];

        try {
            DB::beginTransaction();
            #Roles Table
            foreach ($role_data as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                DB::table("roles")->insert($v);
            }

            #Admin user
            foreach ($users_data as $k => $v) {
                $v["uuid"] = strtolower(str::uuid()->toString());
                $v["doc_dir"] = strtolower(str::uuid()->toString());
                User::create($v);
            }

            #Employee user
            for ($x = 0; $x <= 50; $x++) {
                User::create([
                    "uuid" => strtolower(str::uuid()->toString()),
                    "name" => "Edigitrix - " . sprintf("%02d", $x),
                    "email" => "emp_" . sprintf("%02d", $x) . "@edigitrix.com",
                    "phone" => "88008800" . sprintf("%02d", $x),
                    "role" => "3",
                    "status" => "1",
                    "deleted" => "1",
                    "password" => Hash::make(123456789),
                    "doc_dir" => strtolower(str::uuid()->toString()),
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
