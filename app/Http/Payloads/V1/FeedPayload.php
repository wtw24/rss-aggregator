<?php

declare(strict_types=1);

namespace App\Http\Payloads\V1;

use Carbon\CarbonInterface;

final readonly class FeedPayload
{
    public function __construct(
        public string $name,
        public string $url,
        public string $user_id,
        public ?CarbonInterface $checked_at = null,
    ) {}

    /**
     * @return array{
     *     name: string,
     *     url: string,
     *     user_id: string,
     *     checked_at: ?CarbonInterface
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'user_id' => $this->user_id,
            'checked_at' => $this->checked_at,
        ];
    }
}
