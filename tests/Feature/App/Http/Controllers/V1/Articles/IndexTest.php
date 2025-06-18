<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\V1\Articles;

use App\Http\Controllers\V1\Articles\IndexController;
use App\Models\Article;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class IndexTest extends TestCase
{
    public function test_unauthenticated_user_gets_correct_status_code(): void
    {
        $response = $this->getJson(
            action(IndexController::class),
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_authenticated_user_gets_correct_status_code(): void
    {
        $this->actingAs(User::factory()->create())->getJson(
            uri: action(IndexController::class),
        )->assertStatus(Response::HTTP_OK);
    }

    public function test_authenticated_user_can_only_see_their_own_resources(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        Article::factory()->for($feed)->count(2)->create();
        Article::factory()->count(10)->create();

        $this->assertEquals(12, Article::query()->count());

        $response = $this->actingAs($user)->getJson(
            action(IndexController::class),
        );

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->count(
                        key: 'data',
                        length: 2,
                    )->etc(),
            );
    }

    public function test_response_comes_in_standard_format(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        Article::factory()->for($feed)->count(2)->create();

        $response = $this->actingAs($user)->getJson(
            action(IndexController::class),
        );

        $response->assertStatus(Response::HTTP_OK);

        $data = $response->json('data');

        $this->assertIsArray($data);
        $this->assertCount(2, $data);

        $first = collect($data)->first();

        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('title', $first);
        $this->assertArrayHasKey('summary', $first);
        $this->assertArrayHasKey('link', $first);
        $this->assertArrayHasKey('published_at', $first);
    }

    public function test_response_structure_meets_api_design_expectations(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        Article::factory()->for($feed)->create();

        $response = $this->actingAs($user)->getJson(
            action(IndexController::class),
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'guid',
                        'title',
                        'summary',
                        'link',
                        'published_at' => [
                            'human',
                            'string',
                            'local',
                            'timestamp',
                        ],
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_response_is_paginated(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        $perPage = 15;

        Article::factory()->for($feed)->count($perPage + 1)->create();

        $response = $this->actingAs($user)->getJson(
            action(IndexController::class),
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data',
                'links' => ['first', 'next'],
                'meta' => [
                    'current_page',
                    'current_page_url',
                    'from',
                    'path',
                    'per_page',
                    'to',
                ],
            ])
            ->assertJsonPath('meta.per_page', $perPage)
            ->assertJsonCount($perPage, 'data');
    }

    public function test_user_can_filter_request_to_get_specific_data(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        Article::factory()->for($feed)->create(['title' => 'title text']);
        Article::factory()->for($feed)->create(['title' => 'Other title']);

        $response = $this->actingAs($user)->getJson(
            action(IndexController::class, ['filter[title]' => 'title text']),
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'title text');
    }

    public function test_user_can_sort_results_in_required_order(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        Article::factory()->for($feed)->create(['title' => 'Zebra']);
        Article::factory()->for($feed)->create(['title' => 'Alpha']);

        $response = $this->actingAs($user)->getJson(
            action(IndexController::class, ['sort' => 'title']),
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.0.title', 'Alpha')
            ->assertJsonPath('data.1.title', 'Zebra');
    }
}
