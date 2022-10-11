<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

/**
 * Class PaymentController
 *
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * PaymentController constructor.
     */
    public function __construct()
    {
        $this->middleware('type:admin');
    }

    /**
     * @return PaymentCollection
     */
    public function index()
    {
        try {
            $payments = Payment::with('user')->paginate();
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        return new PaymentCollection($payments);
    }

    /**
     * @param StorePaymentRequest $request
     *
     * @return PaymentResource
     */
    public function store(StorePaymentRequest $request)
    {
        $payment = Payment::create($request->only('user_id', 'total_amount'));

        return new PaymentResource($payment);
    }

    /**
     * @param Payment $payment
     *
     * @return PaymentResource
     */
    public function show(Payment $payment)
    {
        return new PaymentResource($payment);
    }

    /**
     * @param UpdatePaymentRequest $request
     * @param Payment $payment
     *
     * @return PaymentResource
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->only('user_id', 'total_amount'));

        return new PaymentResource($payment);
    }

    /**
     * @param Payment $payment
     *
     * @return JsonResource
     */
    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                'code' => Response::HTTP_BAD_REQUEST
            ]);
        }

        return response()->json([
            'message' => Response::$statusTexts[Response::HTTP_OK],
            'code' => Response::HTTP_OK
        ]);
    }
}
