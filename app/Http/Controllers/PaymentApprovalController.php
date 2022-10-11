<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PaymentApprovalRepository;
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
 * @package App\Http\Controllers
 */
class PaymentApprovalController extends Controller
{
    /**
     * @var PaymentApprovalRepository
     */
    protected $approvalRepository;

    /**
     * PaymentApprovalController constructor.
     *
     * @param PaymentApprovalRepository $approvalRepository
     */
    public function __construct(PaymentApprovalRepository $approvalRepository)
    {
        $this->middleware('type:admin', ['except' => 'paymentApproval']);
        $this->approvalRepository = $approvalRepository;
    }

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
     *
     * @return PaymentApprovalResource|\Illuminate\Http\JsonResponse
     */
    public function paymentApproval(InsertPaymentApprovalRequest $request)
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
                $user->id,
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
