<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Responses\V1\MessageResponse;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final readonly class EmailVerificationNotificationController
{
    #[OA\Post(
        path: '/email/verification-notification',
        operationId: 'auth.verification-notification',
        summary: 'Resending the Verification Email',
        tags: ['Authorization'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Verification link sent!',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'A new verification link has been sent to your email address.'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Email already verified.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Email already verified.'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_TOO_MANY_REQUESTS,
                description: 'Too Many Requests',
            ),
        ]
    )]
    public function __invoke(Request $request): Responsable
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return new MessageResponse(
                message: 'Email already verified.',
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user->sendEmailVerificationNotification();

        return new MessageResponse(
            message: 'A new verification link has been sent to your email address.',
            status: Response::HTTP_OK,
        );
    }
}
