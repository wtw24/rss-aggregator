<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Jobs\Auth\ProcessEmailVerification;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\RedirectResponse;

final readonly class VerifyEmailController
{
    public function __construct(
        private Dispatcher $bus
    ) {}

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->getVerifiedUser();

        if ($user->hasVerifiedEmail()) {
            return redirect()->to(
                path: '/login?verified=already'
            );
        }

        $this->bus->dispatch(
            command: new ProcessEmailVerification(
                user: $user,
            ),
        );

        return redirect()->to(
            path: '/login?verified=success'
        );
    }
}
