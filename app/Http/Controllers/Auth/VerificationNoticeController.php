<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

final class VerificationNoticeController
{
    public function __invoke(Request $request): View
    {
        $status = $request->query('verified');

        $messages = [
            'success' => [
                'title' => 'Email verified!',
                'body' => 'Your email address has been successfully verified.',
                'type' => 'success',
            ],
            'already' => [
                'title' => 'Email was already verified',
                'body' => 'This email address has already been verified.',
                'type' => 'info',
            ],
        ];

        $notification = Arr::get($messages, $status);

        return view('auth.login', [
            'notification' => $notification,
        ]);
    }
}
