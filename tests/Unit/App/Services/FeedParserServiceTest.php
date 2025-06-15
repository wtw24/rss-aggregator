<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services;

use App\Data\ArticleData;
use App\Models\Article;
use App\Models\Feed;
use App\Services\FeedParserService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laminas\Feed\Reader\Entry\EntryInterface;
use Laminas\Feed\Reader\Exception\RuntimeException as FeedReaderException;
use Laminas\Feed\Reader\Feed\AbstractFeed as FeedChannel;
use Laminas\Feed\Reader\Reader;
use Mockery;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

final class FeedParserServiceTest extends TestCase
{
    use RefreshDatabase;

    private FeedParserService $service;

    private MockInterface|LoggerInterface $logger;

    private MockInterface|Reader $reader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->reader = Mockery::mock(Reader::class);
        $this->service = new FeedParserService($this->logger, $this->reader);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_parse_returns_empty_collection_when_channel_import_fails(): void
    {
        $feed = Feed::factory()->create(['url' => 'https://example.com/feed']);

        $this->reader
            ->shouldReceive('import')
            ->with('https://example.com/feed')
            ->andThrow(new FeedReaderException('Feed parsing failed'));

        $this->logger
            ->shouldReceive('error')
            ->once()
            ->with('Failed to parse feed.', [
                'feed_id' => $feed->id,
                'feed_url' => 'https://example.com/feed',
                'exception' => 'Feed parsing failed',
            ]);

        $result = $this->service->parse($feed);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_parse_returns_empty_collection_when_no_articles_found(): void
    {
        $feed = Feed::factory()->create(['url' => 'https://example.com/feed']);

        $channel = Mockery::mock(FeedChannel::class);
        $channel->shouldReceive('rewind')->once();
        $channel->shouldReceive('valid')->once()->andReturn(false);

        $this->reader
            ->shouldReceive('import')
            ->with('https://example.com/feed')
            ->andReturn($channel);

        $result = $this->service->parse($feed);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_parse_extracts_articles_and_filters_existing_ones(): void
    {
        $feed = Feed::factory()->create(['url' => 'https://example.com/feed']);

        $entry1 = $this->createEntryMock('guid-1', 'Article 1', 'Description 1', 'https://example.com/article1', Carbon::now());
        $entry2 = $this->createEntryMock('guid-2', 'Article 2', 'Description 2', 'https://example.com/article2', Carbon::now());
        $entry3 = $this->createEntryMock(null, 'Article 3', 'Description 3', 'https://example.com/article3', Carbon::now());
        $entries = [$entry1, $entry2, $entry3];

        $channel = $this->createChannelMock($entries);

        $this->reader
            ->shouldReceive('import')
            ->with('https://example.com/feed')
            ->andReturn($channel);

        Article::factory()->create(['feed_id' => $feed->id, 'guid' => 'guid-1']);
        $result = $this->service->parse($feed);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $article = $result->first();
        $this->assertInstanceOf(ArticleData::class, $article);
        $this->assertEquals('guid-2', $article->guid);
    }

    public function test_parse_handles_entries_with_missing_required_fields(): void
    {
        $feed = Feed::factory()->create(['url' => 'https://example.com/feed']);
        $entryWithoutGuid = $this->createEntryMock(null, 'Article without GUID', 'Description', 'https://example.com/article', Carbon::now());
        $entryWithoutDate = $this->createEntryMock('guid-1', 'Article without date', 'Description', 'https://example.com/article', null);
        $validEntry = $this->createEntryMock('guid-2', 'Valid Article', 'Valid Description', 'https://example.com/valid-article', Carbon::now());
        $entries = [$entryWithoutGuid, $entryWithoutDate, $validEntry];

        $channel = $this->createChannelMock($entries);

        $this->reader
            ->shouldReceive('import')
            ->with('https://example.com/feed')
            ->andReturn($channel);

        $result = $this->service->parse($feed);
        $this->assertCount(1, $result);
        $this->assertEquals('guid-2', $result->first()->guid);
    }

    public function test_parse_strips_html_tags_from_description(): void
    {
        $feed = Feed::factory()->create(['url' => 'https://example.com/feed']);
        $entry = $this->createEntryMock('guid-1', 'Article with HTML', '<p>Description with <strong>HTML</strong> tags</p>', 'https://example.com/article', Carbon::now());
        $entries = [$entry];

        $channel = $this->createChannelMock($entries);

        $this->reader
            ->shouldReceive('import')
            ->with('https://example.com/feed')
            ->andReturn($channel);

        $result = $this->service->parse($feed);
        $this->assertEquals('Description with HTML tags', $result->first()->summary);
    }

    private function createEntryMock(?string $id, string $title, string $description, string $link, ?\DateTimeInterface $dateModified): MockInterface
    {
        $entry = Mockery::mock(EntryInterface::class);
        $entry->shouldReceive('getId')->andReturn($id);
        $entry->shouldReceive('getTitle')->andReturn($title);
        $entry->shouldReceive('getDescription')->andReturn($description);
        $entry->shouldReceive('getLink')->andReturn($link);
        $entry->shouldReceive('getDateModified')->andReturn($dateModified);

        return $entry;
    }

    /**
     * Helper method to create a configured FeedChannel mock.
     *
     * The returned mock will iterate over the provided array of entries.
     *
     * @param  array  $entries  An array of EntryInterface mocks to iterate over.
     * @return MockInterface A fully configured FeedChannel mock.
     */
    private function createChannelMock(array $entries): MockInterface
    {
        $validReturns = array_fill(0, count($entries), true);
        $validReturns[] = false;

        $channel = Mockery::mock(FeedChannel::class);
        $channel->shouldReceive('rewind')->once();
        $channel->shouldReceive('valid')->times(count($entries) + 1)->andReturn(...$validReturns);
        $channel->shouldReceive('current')->times(count($entries))->andReturn(...$entries);
        $channel->shouldReceive('key')->times(count($entries))->andReturn(...array_keys($entries));
        $channel->shouldReceive('next')->times(count($entries));

        return $channel;
    }
}
