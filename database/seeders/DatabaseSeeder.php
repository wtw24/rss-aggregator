<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\FeedType;
use App\Models\Article;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@app.loc',
        ]);

        $feed = Feed::factory()->for($user)->create([
            'name' => 'Laravel News',
            'url' => 'https://laravel-news.com/feed',
            'type' => FeedType::RSS,
        ]);

        User::factory(10)
            ->has(Feed::factory()->count(random_int(1, 5)))
            ->create();

        Article::factory(20)->for($feed)->create();
    }
}
