<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Http\Resources\V1\FeedResource;
use App\Models\Feed;
use App\Traits\QueryBuilderOptions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

final class IndexController
{
    use QueryBuilderOptions;

    public function __invoke(Request $request): ResourceCollection
    {
        $paginator = QueryBuilder::for(Feed::class)
            ->allowedFilters($this->filters())
            ->allowedIncludes($this->includes())
            ->allowedSorts($this->sorts())
            ->where(
                column: 'user_id',
                operator: '=',
                value: auth()->id()
            )->simplePaginate();

        return FeedResource::collection($paginator);
    }
}
