<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\ArticleData;
use App\Models\Article;
use App\Models\Feed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laminas\Feed\Reader\Entry\Atom as AtomEntry;
use Laminas\Feed\Reader\Entry\EntryInterface;
use Laminas\Feed\Reader\Entry\Rss as RssEntry;
use Laminas\Feed\Reader\Exception\RuntimeException as FeedReaderException;
use Laminas\Feed\Reader\Feed\AbstractFeed as FeedChannel;
use Laminas\Feed\Reader\Reader;
use Psr\Log\LoggerInterface;

final readonly class FeedParserService
{
    public function __construct(
        private LoggerInterface $logger,
        private Reader $reader,
    ) {}

    /**
     * @return Collection<int, ArticleData>
     */
    public function parse(Feed $feed): Collection
    {
        $channel = $this->importChannel($feed);

        if ($channel === null) {
            /** @var Collection<int, ArticleData> */
            return collect();
        }

        $articlesFromFeed = $this->extractArticlesFromChannel($channel);

        if ($articlesFromFeed->isEmpty()) {
            /** @var Collection<int, ArticleData> */
            return collect();
        }

        return $this->filterExistingArticles($feed, $articlesFromFeed);
    }

    /**
     * @return ?FeedChannel<AtomEntry|RssEntry>
     */
    private function importChannel(Feed $feed): ?FeedChannel
    {
        try {
            /** @var FeedChannel<AtomEntry|RssEntry> $channel */
            $channel = $this->reader->import($feed->url);

            return $channel;
        } catch (FeedReaderException $e) {
            $this->logger->error('Failed to parse feed.', [
                'feed_id' => $feed->id,
                'feed_url' => $feed->url,
                'exception' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * @param  FeedChannel<AtomEntry|RssEntry>  $channel
     * @return Collection<int, ArticleData>
     */
    private function extractArticlesFromChannel(FeedChannel $channel): Collection
    {
        /** @var Collection<int, ArticleData> */
        return collect($channel)
            ->map(static function (EntryInterface $item): ?ArticleData {
                $guid = $item->getId();
                $publishedAt = $item->getDateModified();

                if ($guid === null || $publishedAt === null) {
                    return null;
                }

                return new ArticleData(
                    guid: $guid,
                    title: $item->getTitle(),
                    summary: strip_tags((string) $item->getDescription()),
                    link: (string) $item->getLink(),
                    published_at: Carbon::instance($publishedAt),
                );
            })
            ->filter(static fn (?ArticleData $article): bool => $article !== null)
            ->values();
    }

    /**
     * @param  Collection<int, ArticleData>  $articles
     * @return Collection<int, ArticleData>
     */
    private function filterExistingArticles(Feed $feed, Collection $articles): Collection
    {
        $guidsFromFeed = $articles->pluck('guid')->all();

        if (empty($guidsFromFeed)) {
            /** @var Collection<int, ArticleData> */
            return collect();
        }

        /** @var list<string> $existingGuids */
        $existingGuids = Article::query()
            ->where('feed_id', $feed->id)
            ->whereIn('guid', $guidsFromFeed)
            ->get(['guid'])
            ->map(static fn (Article $article): string => $article->guid)
            ->all();

        $existingGuidsSet = array_flip($existingGuids);

        /** @var Collection<int, ArticleData> */
        return $articles
            ->filter(static fn (ArticleData $article): bool => ! isset($existingGuidsSet[$article->guid]))
            ->values();
    }
}
