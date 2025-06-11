<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final class LoginController
{
    /**
     * @throws ValidationException
     */
    #[OA\Post(
        path: '/login',
        operationId: 'auth.login',
        description: 'Authenticates user and returns access token',
        summary: 'Login user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')
        ),
        tags: ['Authorization'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Login successful.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: '1|sometokenvalue...'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User not found.'),
                    ]
                )
            ),
        ]
    )]
    public function __invoke(LoginRequest $request): Response
    {
        $request->authenticate();

        $user = auth()->user();

        if ($user === null) {
            return new JsonResponse(
                data: ['message' => 'User not found.'],
                status: Response::HTTP_UNAUTHORIZED
            );
        }

        $token = $user->createToken(
            name: 'api-auth',
        );

        return new JsonResponse(
            data: [
                'token' => $token->plainTextToken,
            ],
        );
    }
}
