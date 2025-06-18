<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Models\Feed;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class DeleteController
{
    use AuthorizesRequests;

    /**
     * @throws AuthorizationException
     */
    public function __invoke(Request $request, Feed $feed): Response
    {
        $this->authorize('delete', $feed);

        $feed->delete();

        return response()->noContent();
    }
}
