<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Articles;

use App\Http\Resources\V1\ArticleResource;
use App\Models\User;
use App\Traits\QueryBuilderOptions;
use Dedoc\Scramble\Attributes\Group;
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

    #[Group('Articles')]
    public function __invoke(Request $request): ResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $paginator = QueryBuilder::for($user->articles())
            ->allowedFilters($this->filters())
            ->allowedIncludes($this->includes())
            ->allowedSorts($this->sorts())
            ->simplePaginate();

        return ArticleResource::collection($paginator);
    }
}
