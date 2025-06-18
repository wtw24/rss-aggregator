<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Data\ApiError;
use App\Enums\Status;
use App\Factories\HeaderFactory;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class ErrorResponse implements Responsable
{
    public function __construct(
        private ApiError $data,
        private Status $status = Status::HTTP_INTERNAL_SERVER_ERROR,
    ) {}

    /** @param  Request  $request */
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: $this->data->toArray(),
            status: $this->status->value,
            headers: HeaderFactory::error(),
        );
    }
}
