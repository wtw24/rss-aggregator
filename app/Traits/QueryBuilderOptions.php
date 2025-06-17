<?php

declare(strict_types=1);

namespace App\Traits;

trait QueryBuilderOptions
{
    /**
     * @return list<string>
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * @return list<string>
     */
    public function sorts(): array
    {
        return [];
    }

    /**
     * @return list<string>
     */
    public function includes(): array
    {
        return [];
    }
}
