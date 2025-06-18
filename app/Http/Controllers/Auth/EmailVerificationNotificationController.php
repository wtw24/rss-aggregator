<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Responses\V1\MessageResponse;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class EmailVerificationNotificationController
{
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
