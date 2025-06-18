<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Http\Requests\V1\FeedRequest;
use App\Http\Resources\V1\FeedResource;
use App\Models\Feed;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

final class UpdateController
{
    use AuthorizesRequests;

    /**
     * @throws AuthorizationException
     */
    #[Group('Feeds')]
    public function __invoke(FeedRequest $request, Feed $feed): FeedResource
    {
        $feed->update(
            $request->payload()->toArray(),
        );

        $this->authorize('update', $feed);

        return new FeedResource($feed);
    }
}
