<?php

declare(strict_types=1);

namespace App\Factories;

use App\Data\ApiError;
use App\Enums\Status;
use App\Http\Responses\ErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

final class ErrorFactory
{
    public static function create(Throwable $exception, Request $request): JsonResponse|ErrorResponse
    {
        return match ($exception::class) {
            ValidationException::class => new JsonResponse(
                data: [
                    'message' => $exception->getMessage(),
                    'errors' => $exception->errors(),
                ],
                status: Status::HTTP_UNPROCESSABLE_ENTITY->value,
            ),

            UnprocessableEntityHttpException::class => new JsonResponse(
                data: $exception->getMessage(),
                status: Status::HTTP_UNPROCESSABLE_ENTITY->value,
            ),

            NotFoundHttpException::class,
            ModelNotFoundException::class => new ErrorResponse(
                data: new ApiError(
                    title: 'Resource not found.',
                    detail: 'The requested resource was not found.',
                    instance: $request->fullUrl(),
                    code: 'HTTP-404',
                    link: 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/404',
                ),
                status: Status::HTTP_NOT_FOUND,
            ),

            MethodNotAllowedHttpException::class,
            MethodNotAllowedException::class => new ErrorResponse(
                data: new ApiError(
                    title: 'Method not allowed.',
                    detail: sprintf(
                        'The request method %s is not allowed on this resource.',
                        $request->method(),
                    ),
                    instance: $request->fullUrl(),
                    code: 'HTTP-405',
                    link: 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/405',
                ),
                status: Status::HTTP_METHOD_NOT_ALLOWED,
            ),

            AuthenticationException::class => new ErrorResponse(
                data: new ApiError(
                    title: 'Unauthorized.',
                    detail: 'The request was not authenticated.',
                    instance: $request->fullUrl(),
                    code: 'HTTP-401',
                    link: 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/401',
                ),
                status: Status::HTTP_UNAUTHORIZED,
            ),

            default => new ErrorResponse(
                data: new ApiError(
                    title: 'Internal server error.',
                    detail: $exception->getMessage()
                        ? "{$exception->getMessage()}"
                        : '',
                    instance: $request->fullUrl(),
                    code: 'SER-500',
                    link: 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/500',
                ),
                status: Status::HTTP_INTERNAL_SERVER_ERROR,
            ),
        };
    }
}
