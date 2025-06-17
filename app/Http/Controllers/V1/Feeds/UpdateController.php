<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Http\Requests\V1\FeedRequest;
use App\Http\Resources\V1\FeedResource;
use App\Models\Feed;

final class UpdateController
{
    public function __invoke(FeedRequest $request, Feed $feed): FeedResource
    {
        $feed->update(
            $request->payload()->toArray(),
        );

        return new FeedResource($feed);
    }
}
