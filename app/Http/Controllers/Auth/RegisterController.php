<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final readonly class RegisterController
{
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
