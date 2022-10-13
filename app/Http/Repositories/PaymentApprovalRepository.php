<?php


namespace App\Http\Repositories;


use App\Models\PaymentApproval;

/**
 * Class PaymentApprovalRepository
 *
 * @package App\Http\Repositories
 */
class PaymentApprovalRepository
{
    /**
     * @param $userID
     * @param $data
     *
     * @return mixed
     */
    public function insertPaymentApproval($userID, $data)
    {
        return PaymentApproval::create([
            'user_id' => $userID,
            'payment_id' => $data['payment_id'],
            'payment_type' => $data['payment_type'],
            'status' => $data['status']
        ]);
    }

}
