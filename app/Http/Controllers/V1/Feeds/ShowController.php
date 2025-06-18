<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Http\Resources\V1\FeedResource;
use App\Models\Feed;
use App\Traits\QueryBuilderOptions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

final class ShowController
{
    use AuthorizesRequests;
    use QueryBuilderOptions;

    /**
     * @throws AuthorizationException
     */
    public function __invoke(Request $request, string $id): JsonResource
    {
        $feed = QueryBuilder::for(Feed::class)
            ->allowedIncludes($this->includes())
            ->where('id', $id)
            ->firstOrFail();

        $this->authorize('view', $feed);

        return new FeedResource($feed);
    }
}
