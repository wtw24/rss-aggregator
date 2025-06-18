<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

final class ArticlePolicy
{
    public function view(User $user, Article $article): bool
    {
        return $user->id === $article->feed->user_id;
    }
}
