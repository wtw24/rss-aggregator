<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

final readonly class VerifyEmailController
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->getVerifiedUser();

        if ($user->hasVerifiedEmail()) {
            return redirect()->to(
                path: '/email/verify?verified=already'
            );
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return redirect()->to(
            path: '/email/verify?verified=success'
        );
    }
}
