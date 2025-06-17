<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\FeedFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string $url
 * @property string $user_id
 * @property null|CarbonInterface $checked_at
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 * @property-read User $user
 */
final class Feed extends Model
{
    /** @use HasFactory<FeedFactory> */
    use HasFactory;

    use HasUlids;

    /** @return list<string> */
    protected $fillable = [
        'name',
        'url',
        'user_id',
        'checked_at',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    /** @return HasMany<Article, $this> */
    public function articles(): HasMany
    {
        return $this->hasMany(
            related: Article::class,
            foreignKey: 'feed_id',
        );
    }

    /** @return array<string,string|class-string> */
    protected $casts = [
        'checked_at' => 'datetime',
    ];
}
