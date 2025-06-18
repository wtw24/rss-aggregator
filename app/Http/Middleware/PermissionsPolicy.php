<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class PermissionsPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        /** @var string $permissionsPolicy */
        $permissionsPolicy = config('headers.permissions-policy');

        $response->headers->set(
            key: 'Permissions-Policy',
            values: strval($permissionsPolicy),
        );

        return $response;
    }
}
