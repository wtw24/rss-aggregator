<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Feed;
use App\Models\User;

final class FeedPolicy
{
    public function view(User $user, Feed $feed): bool
    {
        return $user->id === $feed->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, Feed $feed): bool
    {
        return $user->id === $feed->user_id;
    }

    public function delete(User $user, Feed $feed): bool
    {
        return $user->id === $feed->user_id;
    }
}
