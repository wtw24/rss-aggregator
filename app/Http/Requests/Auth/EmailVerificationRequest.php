<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use LogicException;

final class EmailVerificationRequest extends FormRequest
{
    private ?User $verifiedUser = null;

    public function authorize(): bool
    {
        $this->verifiedUser = User::find($this->route('id'));

        if (! $this->verifiedUser) {
            return false;
        }

        // @phpstan-ignore-next-line
        return hash_equals(sha1($this->verifiedUser->getEmailForVerification()), (string) $this->route('hash'));
    }

    public function getVerifiedUser(): User
    {
        if ($this->verifiedUser === null) {
            throw new LogicException('Cannot retrieve user before authorization.');
        }

        return $this->verifiedUser;
    }
}
