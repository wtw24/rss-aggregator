<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Feed> */
final class FeedFactory extends Factory
{
    /** @var class-string<Feed> */
    protected $model = Feed::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(
                nbWords: 4,
            ),
            'url' => $this->faker->unique()->url(),
            'user_id' => User::factory(),
            'checked_at' => $this->faker->dateTimeThisMonth(),
        ];
    }

    public function unpublished(): FeedFactory
    {
        return $this->state(static fn (array $attributes) => [
            'published_at' => null,
        ]);
    }
}
