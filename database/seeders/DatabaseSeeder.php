<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(TravelPaymentSeeder::class);
        $this->call(PaymentApprovalSeeder::class);
        $this->call(AuthTokenSeeder::class);
    }
}
