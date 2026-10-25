<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

/**
 * Base controller. Every controller in the app should extend this so all
 * API responses share one consistent envelope:
 *
 *   { "success": true,  "message": "...", "data": ... }
 *   { "success": false, "message": "...", "errors": ... }
 *
 * That consistency is what lets a single frontend/mobile-app response
 * interceptor handle every endpoint the same way.
 */
abstract class Controller
{
    /**
     * 200/201/... success response.
     */
    protected function success(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Shortcut for 201 Created.
     */
    protected function created(mixed $data = null, string $message = 'Created'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
     * Shortcut for a 200 with no body — successful delete/action.
     */
    protected function noContentSuccess(string $message = 'Deleted'): JsonResponse
    {
        return $this->success(null, $message, 200);
    }

    /**
     * 4xx/5xx error response. $errors typically holds validation errors
     * (field => [messages]) or a debug payload in non-production envs.
     */
    protected function error(string $message = 'Something went wrong', int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    protected function unauthorized(string $message = 'Unauthenticated'): JsonResponse
    {
        return $this->error($message, 401);
    }

    protected function forbidden(string $message = 'This action is unauthorized'): JsonResponse
    {
        return $this->error($message, 403);
    }

    protected function validationError(mixed $errors, string $message = 'The given data was invalid'): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Wraps a paginator so the frontend gets both the page of items and
     * the pagination metadata in a predictable shape, instead of Laravel's
     * default paginator JSON (which mixes data with meta at the top level).
     */
    protected function paginatedSuccess(LengthAwarePaginator $paginator, string $message = 'Success'): JsonResponse
    {
        return $this->success([
            'items' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ], $message);
    }
}
