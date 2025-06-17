<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Http\Requests\V1\FeedRequest;
use App\Http\Resources\V1\FeedResource;
use App\Models\Feed;
use Illuminate\Http\Resources\Json\JsonResource;

final class StoreController
{
    public function __invoke(FeedRequest $request): JsonResource
    {
        $feed = Feed::factory()->create(
            $request->payload()->toArray(),
        );

        return new FeedResource($feed);
    }
}
