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
    public function getSumOfApprovedPayments()
    {
        dd(PaymentApproval::with(['payment', 'travelPayments'])->take(2)->get());
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
