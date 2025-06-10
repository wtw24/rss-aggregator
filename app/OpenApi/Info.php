<?php

declare(strict_types=1);

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API documentation for the RSS Aggregator project',
    title: 'RSS Aggregator API',
)]
final class Info {}
