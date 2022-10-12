<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PaymentRepository;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentReportCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

/**
 * Class PaymentController
 *
 * @OA\Schema(
 *      schema="Payments",
 *      type="object"
 * )
 *
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * @var PaymentRepository
     */
    protected $paymentRepository;


    /**
     * PaymentController constructor.
     *
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->middleware('type:admin');
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @OA\Get(
     *     path="/payments",
     *     tags={"Payments"},
     *     summary="Get",
     *     @OA\Response(response="200", description="success",
     *          @OA\JsonContent(ref="#/components/schemas/Payments")))
     * )
     *
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
     * @OA\Post(
     *     path="/payments",
     *     tags={"Payments"},
     *     operationId="StorePayment",
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
     *                     property="total_amount",
     *                     description="Total Amount",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/payments/{id}",
     *     tags={"Payments"},
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
     *          @OA\JsonContent(ref="#/components/schemas/Payments")))
     * )
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
     * @OA\Put(
     *     path="/payments",
     *     tags={"Payments"},
     *     operationId="UpdatePayment",
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
     *                     property="total_amount",
     *                     description="Total Amount",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/payments/{id}",
     *     tags={"Payments"},
     *     summary="Delete",
     *     operationId="DeletePayment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Payment ID to delete",
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

    /**
     *
     * @OA\Get(
     *     path="/payment/report",
     *     tags={"Payments"},
     *     summary="Get",
     *     @OA\Response(response="200", description="OK",
     *     @OA\Response(response="400", description="Bad Request",
     *          @OA\JsonContent(ref="#/components/schemas/Payments")))
     * )
     *
     * @return PaymentReportCollection|\Illuminate\Http\JsonResponse
     */
    public function paymentReport()
    {
        try {
            $report = $this->paymentRepository->getSumOfApprovedPayments();
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                'code' => Response::HTTP_BAD_REQUEST
            ]);
        }

        return new PaymentReportCollection($report);
    }
}
