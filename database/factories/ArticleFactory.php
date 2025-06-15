<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use App\Models\Feed;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Article> */
final class ArticleFactory extends Factory
{
    /** @var class-string<Article> */
    protected $model = Article::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'guid' => $this->faker->uuid(),
            'title' => $this->faker->sentence(
                nbWords: 6,
            ),
            'summary' => $this->faker->sentence(),
            'link' => $this->faker->url(),
            'feed_id' => Feed::factory(),
            'published_at' => $this->faker->dateTimeThisMonth(),
        ];
    }

    public function unpublished(): ArticleFactory
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }
}
