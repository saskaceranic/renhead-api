<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }

        return response()->json([
            'data' => $user,
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
            'code' => Response::HTTP_OK
        ]);
    }

    /**
     * @param Request $request
     *
     * @return AuthResource|JsonResponse
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('first_name', 'password'))) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
                'code' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        try {
            $user = User::findOrFail(Auth::user()->getAuthIdentifier());
        } catch (\Exception $e) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        $success['token'] = $user->createToken('api_token')->plainTextToken;
        $success['first_name'] = $user->first_name;

        return new AuthResource($success);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        try {
            Auth::user()->currentAccessToken()->delete();
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
