<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use App\Http\Payloads\CreateUserPayload;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Queue\Queueable;

final class CreateNewUserJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly CreateUserPayload $payload,
    ) {}

    public function handle(DatabaseManager $databaseManager): void
    {
        $databaseManager->transaction(
            callback: fn () => User::query()->create(
                attributes: $this->payload->toArray(),
            ),
            attempts: 3,
        );
    }
}
