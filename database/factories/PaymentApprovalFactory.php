<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentApproval>
 */
class PaymentApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->where('type', 'approver')->first()->id,
            'payment_id' => Payment::inRandomOrder()->first()->id,
            'payment_type' => fake()->word,
            'status' => fake()->randomElement(['approved', 'disapproved'])
        ];
    }
}
