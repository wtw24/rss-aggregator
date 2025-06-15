<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonInterface;

final readonly class ArticleData
{
    public function __construct(
        public string $guid,
        public string $title,
        public string $summary,
        public string $link,
        public CarbonInterface $published_at,
    ) {}
}
