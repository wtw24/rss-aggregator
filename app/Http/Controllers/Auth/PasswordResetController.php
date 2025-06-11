<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\PasswordResetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final class PasswordResetController
{
    #[OA\Post(
        path: '/forgot-password',
        operationId: 'auth.forgot.password',
        description: 'Sends password reset token to user email.',
        summary: 'Forgot Password',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PasswordResetRequest')
        ),
        tags: ['Authorization'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Password reset token sent!',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'If an account matching your email exists, you will receive instructions to reset your password shortly.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function __invoke(PasswordResetRequest $request): JsonResponse
    {
        Password::sendResetLink($request->only('email'));

        return new JsonResponse(
            data: [
                'message' => 'If an account matching your email exists, you will receive instructions to reset your password shortly.',
            ],
            status: Response::HTTP_OK,
        );
    }
}
