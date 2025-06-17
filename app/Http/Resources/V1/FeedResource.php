<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Feed
 */
final class FeedResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'checked_at' => new DateResource($this->checked_at),
        ];
    }
}
