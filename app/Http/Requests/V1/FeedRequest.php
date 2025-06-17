<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Http\Payloads\V1\FeedPayload;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class FeedRequest extends FormRequest
{
    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'min:11', 'max:255'],
        ];
    }

    public function payload(): FeedPayload
    {
        /** @var User $user */
        $user = $this->user();

        return new FeedPayload(
            name: $this->string('name')->toString(),
            url: $this->string('url')->toString(),
            user_id: $user->id,
        );
    }
}
