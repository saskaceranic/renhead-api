<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PaymentApprovalRepository;
use App\Http\Repositories\PaymentRepository;
use App\Http\Requests\InsertPaymentApprovalRequest;
use App\Http\Requests\StorePaymentApprovalRequest;
use App\Http\Requests\UpdatePaymentApprovalRequest;
use App\Http\Resources\PaymentApprovalCollection;
use App\Http\Resources\PaymentApprovalResource;
use App\Models\PaymentApproval;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaymentApprovalController
 *
 * @OA\Schema(
 *      schema="Payment Approvals",
 *      type="object"
 * )
 *
 * @package App\Http\Controllers
 */
class PaymentApprovalController extends Controller
{
    /**
     * @var PaymentRepository
     */
    protected $approvalRepository;

    /**
     * PaymentApprovalController constructor.
     *
     * @param PaymentApprovalRepository $approvalRepository
     */
    public function __construct(PaymentApprovalRepository $approvalRepository)
    {
        $this->middleware('type:admin', ['except' => ['paymentApproval']]);
        $this->approvalRepository = $approvalRepository;
    }

    /**
     *
     * @OA\Get(
     *     path="/payment-approvals",
     *     tags={"Payment Approvals"},
     *     summary="Get",
     *     @OA\Response(response="200", description="success",
     *          @OA\JsonContent(ref="#/components/schemas/Payment Approvals")))
     * )
     *
     * @return PaymentApprovalCollection
     */
    public function index()
    {
        try {
            $paymentApproval = PaymentApproval::with('user', 'paymentable')->paginate();
        } catch (\Exception $e) {
            dd($e);
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
     * @OA\Post(
     *     path="/payment-approvals",
     *     tags={"Payment Approvals"},
     *     operationId="Store",
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
     *                     property="payment_id",
     *                     description="Payment ID",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="payment_type",
     *                     description="Payment Type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     description="Status",
     *                     type="string",
     *                     enum={"approver", "admin"}
     *                 ),
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/payment-approvals/{id}",
     *     tags={"Payment Approvals"},
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
     *          @OA\JsonContent(ref="#/components/schemas/Payment Approvals")))
     * )
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
     * @OA\Put(
     *     path="/payment-approvals",
     *     tags={"Payment Approvals"},
     *     operationId="Update",
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
     *                     property="payment_id",
     *                     description="Payment ID",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="payment_type",
     *                     description="Payment Type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     description="Status",
     *                     type="string",
     *                     enum={"approver", "admin"}
     *                 ),
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/payment-approvals/{id}",
     *     tags={"Payment Approvals"},
     *     summary="Delete",
     *     operationId="Delete",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Payment Approval ID to delete",
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
     * @return JsonResource
     */
    public function destroy(PaymentApproval $paymentApproval)
    {
        try {
            $paymentApproval->delete();
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
     * @param InsertPaymentApprovalRequest $request
     * @param $type
     *
     * @OA\Post(
     *     path="/payment/approval",
     *     tags={"Payment Approvals"},
     *     operationId="Insert",
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
     *                     property="payment_id",
     *                     description="Payment ID",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="payment_type",
     *                     description="Payment Type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     description="Status",
     *                     type="string",
     *                     enum={"approved", "disapproved"}
     *                 ),
     *             )
     *         )
     *     )
     * )
     *
     * @return PaymentApprovalResource|\Illuminate\Http\JsonResponse
     */
    public function paymentApproval($type, InsertPaymentApprovalRequest $request)
    {
        $user = Auth::user();

        if (!$user->isApprover()) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
                'code' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        try {
            $approval = $this->approvalRepository->insertPaymentApproval(
                $type,
                $user,
                $request->only('payment_id', 'payment_type', 'status')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                'code' => Response::HTTP_BAD_REQUEST
            ]);
        }

        return new PaymentApprovalResource($approval);
    }
}
