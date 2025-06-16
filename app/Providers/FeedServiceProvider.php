<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Feed\GuzzleFeedClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use Laminas\Feed\Reader\Reader;

final class FeedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Reader::class, function ($app) {
            $guzzleClient = new GuzzleClient;

            $feedHttpClient = new GuzzleFeedClient($guzzleClient);

            Reader::setHttpClient($feedHttpClient);

            return new Reader;
        });
    }
}
