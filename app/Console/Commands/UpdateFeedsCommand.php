<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UpdateFeed;
use App\Models\Feed;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'feeds:update',
    description: 'Parse all feeds and store new articles.',
)]
final class UpdateFeedsCommand extends Command
{
    public function handle(): void
    {
        info('Dispatching feed update jobs...');

        /** @var Feed $feed */
        foreach (Feed::query()->cursor() as $feed) {
            info("Dispatching job for feed: [{$feed->id}] {$feed->name}");

            UpdateFeed::dispatch($feed->id);
        }

        info('All jobs have been dispatched.');
    }
}
