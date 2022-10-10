<?php

namespace Database\Seeders;

use App\Models\TravelPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TravelPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TravelPayment::factory()->count(100)->create();
    }
}
