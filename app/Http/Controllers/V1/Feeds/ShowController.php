<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Feeds;

use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowController
{
    public function __invoke(Request $request): Responsable
    {
        return new MessageResponse(
            message: 'todo',
            status: Response::HTTP_ACCEPTED,
        );
    }
}
