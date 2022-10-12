<?php


namespace App\Http\Repositories;


use App\Models\Payment;
use App\Models\PaymentApproval;

/**
 * Class PaymentApprovalRepository
 *
 * @package App\Http\Repositories
 */
class PaymentRepository
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

    public function getSumOfApprovedPayments()
    {
        $approvals = PaymentApproval::with('travelPayments')->groupBy('payment_id')
            ->having('status', 'approved');

        $payments = Payment::with('user')
            ->selectRaw('*, SUM(total_amount) as sum_amount')
            ->joinSub($approvals, 'pa', function ($join) {
                $join->on('payments.id', '=', 'pa.payment_id');
            })
            ->groupBy('pa.user_id')
            ->get();

        return $payments;
    }
}
