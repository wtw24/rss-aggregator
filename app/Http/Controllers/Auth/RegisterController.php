<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use App\Jobs\Auth\CreateNewUserJob;
use Illuminate\Contracts\Bus\Dispatcher;

final class RegisterController
{
    public function __construct(
        private readonly Dispatcher $bus
    ) {}

    public function __invoke(RegisterRequest $request): void
    {
        $this->bus->dispatch(
            command: new CreateNewUserJob(
                payload: $request->payload(),
            ),
        );
    }
}
