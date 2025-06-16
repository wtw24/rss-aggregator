<?php

declare(strict_types=1);

namespace App\Services\Feed;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Laminas\Feed\Reader\Http\ClientInterface as FeedReaderHttpClientInterface;
use Laminas\Feed\Reader\Http\Psr7ResponseDecorator;

final class GuzzleFeedClient implements FeedReaderHttpClientInterface
{
    public function __construct(
        private readonly GuzzleClientInterface $client,
    ) {}

    public function get($uri): Psr7ResponseDecorator
    {
        return new Psr7ResponseDecorator(
            $this->client->request('GET', $uri)
        );
    }
}
