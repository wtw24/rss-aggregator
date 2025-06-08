<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use App\Http\Payloads\CreateUserPayload;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CreateNewUserJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        public readonly CreateUserPayload $payload,
    ) {}

    public function handle(DatabaseManager $databaseManager): void
    {
        $user = $databaseManager->transaction(
            callback: fn (): User => User::query()->create(
                attributes: $this->payload->toArray()
            ),
            attempts: 3,
        );

        event(new Registered($user));
    }

    public function failed(Throwable $e): void
    {
        Log::error('Failed to create user', [
            'email' => $this->payload->email,
            'job_id' => $this->job?->getJobId(),
            'attempts' => $this->attempts(),
            'exception' => [
                'message' => sanitize_exception_message($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
        ]);
    }
}
