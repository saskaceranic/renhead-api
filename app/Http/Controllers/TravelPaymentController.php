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
 * @OA\Schema(
 *      schema="Travel Payments",
 *      type="object"
 * )
 *
 * @package App\Http\Controllers
 */
class TravelPaymentController extends Controller
{
    /**
     * TravelPaymentController constructor.
     */
    public function __construct()
    {
        $this->middleware('type:admin');
    }

    /**
     *
     * @OA\Get(
     *     path="/travel-payments",
     *     tags={"Travel Payments"},
     *     summary="Get",
     *     @OA\Response(response="200", description="success",
     *          @OA\JsonContent(ref="#/components/schemas/Travel Payments")))
     * )
     *
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
     * @OA\Post(
     *     path="/travel-payments",
     *     tags={"Travel Payments"},
     *     operationId="StoreTravelPayment",
     *     @OA\Response(
     *         response=201,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="User ID",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     description="Amount",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/travel-payments/{id}",
     *     tags={"Travel Payments"},
     *     summary="Get",
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="ID",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="200", description="success",
     *          @OA\JsonContent(ref="#/components/schemas/Travel Payments")))
     * )
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
     * @OA\Put(
     *     path="/travel-payments",
     *     tags={"Travel Payments"},
     *     operationId="UpdateTravelPayment",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="User ID",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     description="Amount",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/travel-payments/{id}",
     *     tags={"Travel Payments"},
     *     summary="Delete",
     *     operationId="DeleteTravelPayment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Travel Payment ID to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     * )
     *
     * @return JsonResource
     */
    public function destroy(TravelPayment $travelPayment)
    {
        try {
            $travelPayment->delete();
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
