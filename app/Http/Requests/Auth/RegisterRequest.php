<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Payloads\CreateUserPayload;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
