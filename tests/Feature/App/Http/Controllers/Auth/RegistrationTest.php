<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use App\Notifications\Auth\QueuedVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        Notification::fake();

        $userData = [
            'name' => 'Test User',
            'email' => 'test@app.loc',
            'password' => 'P@ssw0rd123!',
            'password_confirmation' => 'P@ssw0rd123!',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $userData['email'],
        ]);

        $response = $this->postJson(
            action(RegisterController::class),
            $userData
        );

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertValid();

        $user = User::query()->where('email', $userData['email'])->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, QueuedVerifyEmail::class);
    }
}
