<?php

declare(strict_types=1);

namespace Database\Seeders;

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
        $unverified = User::factory()->unverified()->create([
            'name' => 'Unverified User',
            'email' => 'unverified@app.loc',
            'password' => 'P@ssw0rd123!',
        ]);

        $user = User::factory()->create([
            'name' => 'Verified User',
            'email' => 'verified@app.loc',
            'password' => 'P@ssw0rd123!',
        ]);

        $feed = Feed::factory()->for($user)->create([
            'name' => 'Laravel News',
            'url' => 'https://feed.laravel-news.com',
        ]);

        $feed = Feed::factory()->for($user)->create([
            'name' => 'Selling Partner API',
            'url' => 'https://developer-docs.amazon.com/sp-api/changelog.rss',
        ]);

        // User::factory(10)
        //    ->has(Feed::factory()->count(random_int(1, 5)))
        //    ->create();

        // Article::factory(20)->for($feed)->create();
    }
}
