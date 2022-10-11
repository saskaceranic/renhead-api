<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Class Handler
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            $responseCode = 500;
            $responseData['message'] = $e->getMessage();
            if ($e instanceof ModelNotFoundException) {
                $responseCode = 404;
            } elseif ($e instanceof ValidationException) {
                $responseCode = 422;
                $responseData['errors'] = $e->errors();
            } else if ($e instanceof AuthorizationException || $e instanceof AuthenticationException) {
                $responseCode = 403;
            }

            return response()->json($responseData, $responseCode);
        }

        return parent::render($request, $e);
    }
}
