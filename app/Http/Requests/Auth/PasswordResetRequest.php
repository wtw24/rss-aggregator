<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'PasswordResetRequest',
    required: ['email'],
    properties: [
        new OAT\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'user@example.com'),
    ]
)]
final class PasswordResetRequest extends FormRequest
{
    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
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
