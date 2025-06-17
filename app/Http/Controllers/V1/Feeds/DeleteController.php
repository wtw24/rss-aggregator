<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class DeleteController
{
    public function __invoke(Request $request, Feed $feed): Response
    {
        $feed->delete();

        return response()->noContent();
    }
}
