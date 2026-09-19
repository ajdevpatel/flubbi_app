<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            #$file = database_path("");
            DB::connection()->getPdo()->exec(File::get(base_path("database/db_sql.sql")));
            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}