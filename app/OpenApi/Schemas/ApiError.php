<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ApiError',
    title: 'API Error',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Resource not found.'),
        new OA\Property(property: 'detail', type: 'string', example: 'The resource you are looking for does not exist.'),
        new OA\Property(property: 'instance', type: 'string', format: 'uri', example: 'http://localhost/api/v1/some/resource'),
        new OA\Property(property: 'code', type: 'string', example: 'HTTP-404'),
        new OA\Property(property: 'link', type: 'string', format: 'uri', example: 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/404'),
    ],
    type: 'object'
)]
final class ApiError {}
