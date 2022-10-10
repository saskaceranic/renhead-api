<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create()->each(function () {
            Payment::factory(350)->create();
        });
    }
}
