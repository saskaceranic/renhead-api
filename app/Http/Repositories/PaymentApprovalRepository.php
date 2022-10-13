<?php


namespace App\Http\Repositories;


use App\Models\Payment;
use App\Models\PaymentApproval;
use App\Models\TravelPayment;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Class PaymentApprovalRepository
 *
 * @package App\Http\Repositories
 */
class PaymentApprovalRepository
{

    /**
     * @param $type
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function insertPaymentApproval($type, $user, $data)
    {
        if ($type === 'payment') {
            $payment = Payment::find($data['payment_id']);
        } elseif ($type === 'travel') {
            $payment = TravelPayment::find($data['payment_id']);
        }

        if ($payment == null) {
            throw new NotFoundResourceException();
        }

        $approval = PaymentApproval::create([
            'user_id' => $user['id'],
            'status' => $data['status']
        ]);

        $approval->payment()->associate($payment);
        $approval->save();

        return $approval;
    }

}
