<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Jobs\Auth\CreateNewUserJob;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Support\Responsable;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final readonly class RegisterController
{
    public function __construct(
        private Dispatcher $bus
    ) {}

    #[OA\Post(
        path: '/register',
        operationId: 'auth.register',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest')
        ),
        tags: ['Authorization'],
        responses: [
            new OA\Response(
                response: 202,
                description: 'Successful registration',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'A confirmation link has been sent to your email address. Please check your inbox to complete your registration.'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
            ),
        ]
    )]
    public function __invoke(RegisterRequest $request): Responsable
    {
        $this->bus->dispatch(
            command: new CreateNewUserJob(
                payload: $request->payload(),
            ),
        );

        return new MessageResponse(
            message: 'A confirmation link has been sent to your email address. Please check your inbox to complete your registration.',
            status: Response::HTTP_ACCEPTED,
        );
    }
}
