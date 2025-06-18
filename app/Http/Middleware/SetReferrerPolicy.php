<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetReferrerPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        /** @var string $referrerPolicy */
        $referrerPolicy = config('headers.referrer-policy');

        $response->headers->set(
            key: 'Referrer-Policy',
            values: strval($referrerPolicy),
        );

        return $response;
    }
}
