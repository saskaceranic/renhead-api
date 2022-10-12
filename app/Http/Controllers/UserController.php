<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Response;

/**
 * Class UserController
 *
 * @OA\Schema(
 *      schema="Users",
 *      type="object"
 * )
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('type:admin');
    }

    /**
     *
     * @OA\Get(
     *     path="/users/approver",
     *     tags={"Approvers"},
     *     summary="Get",
     *     @OA\Response(response="200", description="success",
     *          @OA\JsonContent(ref="#/components/schemas/Users")))
     * )
     *
     * @return UserCollection
     */
    public function getAllApprovers()
    {
        try {
            $approvers = User::approver()->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        return new UserCollection($approvers);
    }
}
