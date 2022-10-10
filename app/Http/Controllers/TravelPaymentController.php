<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelPaymentRequest;
use App\Http\Requests\UpdateTravelPaymentRequest;
use App\Http\Resources\TravelPaymentCollection;
use App\Http\Resources\TravelPaymentResource;
use App\Models\TravelPayment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

/**
 * Class TravelPaymentController
 *
 * @package App\Http\Controllers
 */
class TravelPaymentController extends Controller
{

    /**
     * @return TravelPaymentCollection
     */
    public function index()
    {
        try {
            $payments = TravelPayment::with('user')->paginate();
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        return new TravelPaymentCollection($payments);
    }

    /**
     * @param StoreTravelPaymentRequest $request
     *
     * @return TravelPaymentResource
     */
    public function store(StoreTravelPaymentRequest $request)
    {
        $travelPayment = TravelPayment::create($request->only('user_id', 'amount'));

        return new TravelPaymentResource($travelPayment);
    }

    /**
     * @param TravelPayment $payment
     *
     * @return TravelPaymentResource
     */
    public function show(TravelPayment $payment)
    {
        return new TravelPaymentResource($payment);
    }

    /**
     * @param UpdateTravelPaymentRequest $request
     * @param TravelPayment $travelPayment
     *
     * @return TravelPaymentResource
     */
    public function update(UpdateTravelPaymentRequest $request, TravelPayment $travelPayment)
    {
        $travelPayment->update($request->only('user_id', 'amount'));

        return new TravelPaymentResource($travelPayment);
    }

    /**
     * @param TravelPayment $travelPayment
     *
     * @return JsonResource
     */
    public function destroy(TravelPayment $travelPayment)
    {
        try {
            $travelPayment->delete();
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
