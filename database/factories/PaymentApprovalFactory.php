<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\TravelPayment;
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
        $paymentable = $this->faker->randomElement([
            [
                'id' => Payment::all()->random(),
                'type' => Payment::class,
            ],
            [
                'id' => TravelPayment::all()->random(),
                'type' => TravelPayment::class,
            ]
        ]);

        return [
            'user_id' => User::inRandomOrder()->where('type', 'approver')->first()->id,
            'payment_id' => $paymentable['id'],
            'payment_type' => $paymentable['type'],
            'status' => fake()->randomElement(['approved', 'disapproved'])
        ];
    }
}
