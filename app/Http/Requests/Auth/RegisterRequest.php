<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Payloads\Auth\CreateUserPayload;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'RegisterRequest',
    required: ['name', 'email', 'password', 'password_confirmation'],
    properties: [
        new OAT\Property(property: 'name', type: 'string', maxLength: 255, example: 'John Doe'),
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
final class RegisterRequest extends FormRequest
{
    /** @return array<string, ValidationRule|list<mixed>|string> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function payload(): CreateUserPayload
    {
        return new CreateUserPayload(
            $this->string('name')->toString(),
            $this->string('email')->toString(),
            $this->string('password')->toString(),
        );
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
