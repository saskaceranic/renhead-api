<?php


namespace App\Http\Repositories;


use App\Models\Payment;
use App\Models\PaymentApproval;
use App\Models\TravelPayment;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaymentApprovalRepository
 *
 * @package App\Http\Repositories
 */
class PaymentRepository
{
    public function getSumOfApprovedPayments()
    {
        $test = PaymentApproval::whereHasMorph('paymentable', [Payment::class, TravelPayment::class],
            function (Builder $query) {
                $query->groupBy('payment_id')
                    ->having('status', 'approved');
            });

        $payments = Payment::with('user')
            ->selectRaw('*, SUM(total_amount) as sum_amount')
            ->joinSub($test, 'pa', function ($join) {
                $join->on('payments.id', '=', 'pa.payment_id');
            })
            ->groupBy('pa.user_id')
            ->get();

        return $payments;
    }
}
