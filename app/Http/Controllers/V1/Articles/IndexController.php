<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Articles;

use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;
use App\Traits\QueryBuilderOptions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

final class IndexController
{
    use QueryBuilderOptions;

    /** @return list<string> */
    public function filters(): array
    {
        return ['feed_id'];
    }

    /** @return list<string> */
    public function includes(): array
    {
        return ['feed'];
    }

    public function __invoke(Request $request): ResourceCollection
    {
        $paginator = QueryBuilder::for(Article::class)
            ->allowedFilters($this->filters())
            ->allowedIncludes($this->includes())
            ->allowedSorts($this->sorts())
            ->simplePaginate();

        return ArticleResource::collection($paginator);
    }
}
