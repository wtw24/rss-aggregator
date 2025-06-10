<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ProcessEmailVerification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly User $user
    ) {}

    public function handle(): void
    {
        $this->user->markEmailAsVerified();

        event(new Verified($this->user));
    }

    public function failed(Throwable $e): void
    {
        Log::error('Failed to process email verification', [
            'email' => $this->user->email,
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
