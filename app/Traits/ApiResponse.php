<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     */
    public function successResponse(mixed $data, int $code = Response::HTTP_OK): JsonResponse
    {
        $response = ['success' => true];

        if (
            $data instanceof \Illuminate\Http\Resources\Json\ResourceCollection &&
            method_exists($data, 'response') &&
            isset($data->resource) &&
            $data->resource instanceof \Illuminate\Pagination\AbstractPaginator
        ) {
            $response = array_merge($response, $data->response()->getData(true));
        } else {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return an error JSON response.
     */
    public function errorResponse(string $message, int $code): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Return an error message JSON response with custom header.
     */
    public function errorMessage($message, int $code): JsonResponse
    {
        return response()->json($message, $code)->header('Content-Type', 'application/json');
    }

    /**
     * Return a 201 Created response.
     */
    public function createdResponse($data): JsonResponse
    {
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    /**
     * Return a 204 No Content response.
     */
    public function noContentResponse(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Return a 404 Not Found response.
     */
    public function notFoundResponse($message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Return a 403 Forbidden response.
     */
    public function forbiddenResponse($message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Return a 401 Unauthorized response.
     */
    public function unauthorizedResponse($message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a 422 Validation Error response.
     */
    public function validationErrorResponse($message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
