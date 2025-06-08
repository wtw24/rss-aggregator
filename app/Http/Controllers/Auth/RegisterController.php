<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Jobs\Auth\CreateNewUserJob;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

final readonly class RegisterController
{
    public function __construct(
        private Dispatcher $bus
    ) {}

    public function __invoke(RegisterRequest $request): Responsable
    {
        $this->bus->dispatch(
            command: new CreateNewUserJob(
                payload: $request->payload(),
            ),
        );

        return new MessageResponse(
            message: 'A confirmation link has been sent to your email address. Please check your inbox to complete your registration.',
            status: Response::HTTP_ACCEPTED,
        );
    }
}
