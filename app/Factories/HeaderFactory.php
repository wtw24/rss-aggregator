<?php

declare(strict_types=1);

namespace App\Factories;

final class HeaderFactory
{
    /**
     * @param  array<string,string>  $headers
     * @return array<string,string>
     */
    public static function default(array $headers = []): array
    {
        return array_merge(
            [
                'Content-Type' => 'application/json',
            ],
            $headers,
        );
    }

    /**
     * @param  array<string,string>  $headers
     * @return array<string,string>
     */
    public static function error(array $headers = []): array
    {
        return array_merge(
            [
                'Content-Type' => 'application/problem+json',
            ],
            $headers,
        );
    }
}
