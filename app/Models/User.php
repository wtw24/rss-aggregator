<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\Auth\QueuedVerifyEmail;
use App\Notifications\Auth\ResetPasswordNotification;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property null|CarbonInterface $email_verified_at
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 * @property-read Collection<int, Feed> $feeds
 * @property-read Collection<int, Article> $articles
 */
final class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasUlids;
    use Notifiable;
    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new QueuedVerifyEmail);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return HasMany<Feed, $this>
     */
    public function feeds(): HasMany
    {
        return $this->hasMany(
            related: Feed::class,
            foreignKey: 'user_id',
        );
    }

    /**
     * @return HasManyThrough<Article, Feed, $this>
     */
    public function articles(): HasManyThrough
    {
        return $this->hasManyThrough(Article::class, Feed::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
