<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'ResetPasswordRequest',
    required: ['token', 'email', 'password', 'password_confirmation'],
    properties: [
        new OAT\Property(property: 'token', type: 'string', maxLength: 255, example: 'sometokenvalue...'),
        new OAT\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'user@example.com'),
        new OAT\Property(
            property: 'password',
            description: 'Password must be at least 8 characters long and contain mixed case letters, numbers, and symbols.',
            type: 'string',
            format: 'password',
            example: 'P@ssw0rd123!',
        ),
        new OAT\Property(
            property: 'password_confirmation',
            description: 'Confirmation for the password.',
            type: 'string',
            format: 'password',
            example: 'P@ssw0rd123!',
        ),
    ]
)]
final class ResetPasswordRequest extends FormRequest
{
    /** @return array<string, ValidationRule|list<mixed>|string> */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->string(
                key: 'email',
                default: '',
            )->squish()->lower()->value(),
        ]);
    }
}
