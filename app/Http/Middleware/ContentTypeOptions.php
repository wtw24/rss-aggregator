<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContentTypeOptions
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        /** @var string $contentTypeOptions */
        $contentTypeOptions = config('headers.content-type-options');

        $response->headers->set(
            key: 'X-Content-Type-Options',
            values: strval($contentTypeOptions),
        );

        return $response;
    }
}
