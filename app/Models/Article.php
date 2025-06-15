<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $guid
 * @property string $title
 * @property string $summary
 * @property string $link
 * @property string $feed_id
 * @property null|CarbonInterface $published_at
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 * @property-read Feed $feed
 */
final class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    use HasUlids;

    /** @var list<string> */
    protected $fillable = [
        'guid',
        'title',
        'summary',
        'link',
        'feed_id',
        'published_at',
    ];

    /** @return BelongsTo<Feed, $this> */
    public function feed(): BelongsTo
    {
        return $this->belongsTo(
            related: Feed::class,
            foreignKey: 'feed_id',
        );
    }

    /** @return array<string,string|class-string> */
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
