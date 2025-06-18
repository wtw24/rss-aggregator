<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CertificateTransparencyPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        /** @var string $certificateTransparency */
        $certificateTransparency = config('headers.certificate-transparency');

        $response->headers->set(
            key: 'Expect-CT',
            values: strval($certificateTransparency),
        );

        return $response;
    }
}
