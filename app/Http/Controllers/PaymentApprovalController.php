<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentApprovalRequest;
use App\Http\Requests\UpdatePaymentApprovalRequest;
use App\Http\Resources\PaymentApprovalCollection;
use App\Http\Resources\PaymentApprovalResource;
use App\Models\PaymentApproval;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

/**
 * Class PaymentApprovalController
 *
 * @package App\Http\Controllers
 */
class PaymentApprovalController extends Controller
{

    /**
     * @return PaymentApprovalCollection
     */
    public function index()
    {
        try {
            $paymentApproval = PaymentApproval::with(['user', 'payment'])->paginate();
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        return new PaymentApprovalCollection($paymentApproval);
    }

    /**
     * @param StorePaymentApprovalRequest $request
     *
     * @return PaymentApprovalResource
     */
    public function store(StorePaymentApprovalRequest $request)
    {
        $paymentApproval = PaymentApproval::create(
            $request->only('user_id', 'payment_id', 'payment_type', 'status')
        );

        return new PaymentApprovalResource($paymentApproval);
    }

    /**
     * @param PaymentApproval $paymentApproval
     *
     * @return PaymentApprovalResource
     */
    public function show(PaymentApproval $paymentApproval)
    {
        return new PaymentApprovalResource($paymentApproval);
    }

    /**
     * @param UpdatePaymentApprovalRequest $request
     * @param PaymentApproval $paymentApproval
     *
     * @return PaymentApprovalResource
     */
    public function update(UpdatePaymentApprovalRequest $request, PaymentApproval $paymentApproval)
    {
        $paymentApproval->update($request->only('user_id', 'payment_id', 'payment_type', 'status'));

        return new PaymentApprovalResource($paymentApproval);
    }


    /**
     * @param PaymentApproval $paymentApproval
     *
     * @return JsonResource
     */
    public function destroy(PaymentApproval $paymentApproval)
    {
        try {
            $paymentApproval->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED],
                'code' => Response::HTTP_METHOD_NOT_ALLOWED
            ]);
        }

        return response()->json([
            'message' => Response::$statusTexts[Response::HTTP_OK],
            'code' => Response::HTTP_OK
        ]);
    }
}
