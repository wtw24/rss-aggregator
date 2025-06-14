<?php

declare(strict_types=1);

namespace App\Enums;

enum FeedType: string
{
    case RSS = 'rss';

    case ATOM = 'atom';

    public function label(): string
    {
        return match ($this) {
            self::RSS => 'RSS Feed',
            self::ATOM => 'Atom Feed',
        };
    }
}
