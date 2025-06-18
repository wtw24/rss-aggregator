<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class LoginController
{
    /** @throws ValidationException */
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
            status: Response::HTTP_OK,
        );
    }
}
