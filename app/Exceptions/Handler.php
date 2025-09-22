<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $e
     * @return JsonResponse|Response
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions.
     */
    private function handleApiException($request, Throwable $exception): JsonResponse
    {
        if (app()->environment('production')) {
            \Log::error('API Exception', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'exception' => [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'code' => $exception->getCode()
                ]
            ]);
        }

        $statusCode = $this->getStatusCode($exception);

        if (app()->environment('production')) {
            return response()->json([
                'success' => false,
                'message' => $this->getProductionMessage($statusCode),
                'error_code' => $this->getErrorCode($exception)
            ], $statusCode);
        }

        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace()
        ], $statusCode);
    }

    /**
     * Get the HTTP status code.
     */
    private function getStatusCode(Throwable $exception): int
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return 422;
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 404;
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return 405;
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return 404;
        }

        if ($exception instanceof \Illuminate\Database\QueryException) {
            return app()->environment('production') ? 500 : 404;
        }

        return $exception->getCode() >= 100 && $exception->getCode() < 600 ? $exception->getCode() : 500;
    }

    /**
     * Get user-friendly message for production.
     */
    private function getProductionMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Resource not found',
            405 => 'Method not allowed',
            422 => 'Validation failed',
            429 => 'Too many requests',
            500 => 'Internal server error',
            503 => 'Service unavailable',
            default => 'An error occurred'
        };
    }

    /**
     * Get error code for tracking.
     */
    private function getErrorCode(Throwable $exception): ?string
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return 'VALIDATION_ERROR';
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 'RESOURCE_NOT_FOUND';
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return 'METHOD_NOT_ALLOWED';
        }


        $statusCode = $this->getStatusCode($exception);
        return 'ERR_' . $statusCode;
    }
}
