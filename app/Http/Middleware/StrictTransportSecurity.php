<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class StrictTransportSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        /** @var string $strictTransportSecurity */
        $strictTransportSecurity = config('headers.strict-transport-security');

        $response->headers->set(
            key: 'Strict-Transport-Security',
            values: strval($strictTransportSecurity),
        );

        return $response;
    }
}
