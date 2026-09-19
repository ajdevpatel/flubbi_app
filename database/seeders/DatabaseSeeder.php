<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */

        try {
            DB::beginTransaction();
            $this->call([
                ExtraSeeder::class,
                LeadSeeder::class,
                LoanSeeder::class,
                LocationSeeder::class,
                OurPartners::class,
                UsersSeeder::class,
                WebOptions::class,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}