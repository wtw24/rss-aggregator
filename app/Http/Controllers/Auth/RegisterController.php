<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final readonly class RegisterController
{
    #[OA\Post(
        path: '/register',
        operationId: 'auth.register',
        summary: 'Register a new user and get an API token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest')
        ),
        tags: ['Authorization'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Successful registration. Returns user token.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Your account has been created. A confirmation link has been sent to your email address.'
                        ),
                        new OA\Property(
                            property: 'token',
                            type: 'string',
                            example: '1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = DB::transaction(
            callback: static fn (): User => User::query()->create(
                attributes: $request->payload()->toArray()
            ),
            attempts: 2,
        );

        $token = $user->createToken('auth-token')->plainTextToken;

        event(new Registered($user));

        return new JsonResponse(
            data: [
                'message' => 'Your account has been created. A confirmation link has been sent to your email address.',
                'token' => $token,
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
