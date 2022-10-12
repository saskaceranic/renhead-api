<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class PaymentTest
 *
 * @package Tests\Feature
 */
class PaymentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var array
     */
    private $data = [
        'id' => 5555,
        'user_id' => 1,
        'total_amount' => 2585
    ];

    /**
     * @test
     */
    public function createPaymentWithoutToken()
    {
        $response = $this->post('/api/payments', $this->data);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function createPaymentWithToken()
    {
        $response = $this->postByUserType('admin','/api/payments', $this->data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function updatePaymentWithoutToken()
    {
        $response = $this->patch('/api/payments/1', $this->data);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function updatePaymentWithToken()
    {
        $response = $this->patchByUserType('admin', '/api/payments/1', $this->data);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function deletePaymentWithoutToken()
    {
        $response = $this->delete('/api/payments/1');

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function deletePaymentWithToken()
    {
        $response = $this->deleteByUserType('admin', '/api/payments/1');

        $response->assertStatus(200);
    }
}
