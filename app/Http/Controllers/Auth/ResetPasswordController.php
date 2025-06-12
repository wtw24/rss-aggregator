<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final class ResetPasswordController
{
    /** @throws ValidationException */
    #[OA\Post(
        path: '/reset-password',
        operationId: 'auth.reset.password',
        description: 'Reset password for user.',
        summary: 'Reset password',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ResetPasswordRequest')
        ),
        tags: ['Authorization'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Password reset successful.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Your password has been reset.'
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
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        /** @var string $status */
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            /** @var User $user */
            static function (Authenticatable $user) use ($request): void {
                $user->forceFill([
                    'password' => Hash::make($request->string('password')->value()),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return new JsonResponse(
            data: [
                'message' => 'Your password has been reset.',
            ], status: Response::HTTP_OK,
        );
    }
}
