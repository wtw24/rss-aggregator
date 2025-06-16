<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\ArticleData;
use App\Models\Article;
use App\Models\Feed;
use App\Services\FeedParserService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class UpdateFeed implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public readonly string $feedId,
    ) {}

    public function uniqueId(): string
    {
        return (string) $this->feedId;
    }

    /** @throws \Throwable */
    public function handle(FeedParserService $parser, DatabaseManager $database): void
    {
        $feed = Feed::query()->findOrFail($this->feedId);

        info("Parsing feed: {$feed->name}...", ['feed_id' => $feed->id]);

        /** @var Collection<int, ArticleData> $articles */
        $articles = $parser->parse($feed);

        if ($articles->isEmpty()) {
            info('No new articles found.');
            $feed->update(['checked_at' => now()]);

            return;
        }

        $insertedCount = $this->saveArticles($database, $feed, $articles);

        info("Feed update completed. Found: {$articles->count()}, Inserted: {$insertedCount}.", [
            'feed_id' => $feed->id,
        ]);
    }

    /**
     * @param  Collection<int, ArticleData>  $articles
     *
     * @throws \Throwable
     */
    private function saveArticles(DatabaseManager $database, Feed $feed, Collection $articles): int
    {
        return $database->transaction(function () use ($feed, $articles): int {
            $now = Carbon::now();

            $articlesToInsert = $articles->map(static fn (ArticleData $articleData): array => [
                'id' => (string) Str::ulid(),
                'guid' => $articleData->guid,
                'title' => trim($articleData->title),
                'summary' => trim($articleData->summary),
                'link' => $articleData->link,
                'published_at' => $articleData->published_at,
                'feed_id' => $feed->id,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            $insertedCount = Article::query()->insertOrIgnore($articlesToInsert);

            $feed->update(['checked_at' => $now]);

            return $insertedCount;
        });
    }
}
