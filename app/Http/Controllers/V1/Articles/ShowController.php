<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Articles;

use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;
use App\Traits\QueryBuilderOptions;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

final class ShowController
{
    use AuthorizesRequests;
    use QueryBuilderOptions;

    #[Group('Articles')]
    public function __invoke(Request $request, string $id): JsonResource
    {
        $article = QueryBuilder::for(Article::class)
            ->allowedIncludes($this->includes())
            ->where('id', $id)
            ->firstOrFail();

        $this->authorize('view', $article);

        return new ArticleResource($article);
    }
}
