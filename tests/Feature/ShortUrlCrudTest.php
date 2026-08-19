<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_short_url(): void
    {
        $response = $this->postJson('/api/short-urls', [
            'original_url' => 'https://www.tracken.com.br/produto/123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.original_url', 'https://www.tracken.com.br/produto/123')
            ->assertJsonStructure([
                'data' => ['id', 'original_url', 'short_url', 'short_code', 'is_active', 'created_at', 'updated_at'],
            ]);

        $this->assertDatabaseCount('short_urls', 1);
        $this->assertSame(6, strlen($response->json('data.short_code')));
    }

    public function test_original_url_is_required(): void
    {
        $response = $this->postJson('/api/short-urls', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('original_url');

        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_original_url_must_be_a_valid_url(): void
    {
        $response = $this->postJson('/api/short-urls', [
            'original_url' => 'isto-nao-e-uma-url',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('original_url');
    }

    public function test_can_list_short_urls(): void
    {
        ShortUrl::create(['original_url' => 'https://www.tracken.com.br/pagina-a', 'short_code' => 'AAAAAA']);
        ShortUrl::create(['original_url' => 'https://www.tracken.com.br/pagina-b', 'short_code' => 'BBBBBB']);

        $response = $this->getJson('/api/short-urls');

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_can_show_a_single_short_url(): void
    {
        $shortUrl = ShortUrl::create(['original_url' => 'https://www.tracken.com.br/pagina-a', 'short_code' => 'AAAAAA']);

        $response = $this->getJson("/api/short-urls/{$shortUrl->id}");

        $response->assertOk()->assertJsonPath('data.short_code', 'AAAAAA');
    }

    public function test_show_returns_404_for_unknown_id(): void
    {
        $response = $this->getJson('/api/short-urls/999');

        $response->assertNotFound();
    }

    public function test_can_update_the_original_url_keeping_the_same_code(): void
    {
        $shortUrl = ShortUrl::create(['original_url' => 'https://www.tracken.com.br/pagina-antiga', 'short_code' => 'AAAAAA']);

        $response = $this->putJson("/api/short-urls/{$shortUrl->id}", [
            'original_url' => 'https://www.tracken.com.br/pagina-nova',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.original_url', 'https://www.tracken.com.br/pagina-nova')
            ->assertJsonPath('data.short_code', 'AAAAAA');

        $this->assertDatabaseHas('short_urls', [
            'id' => $shortUrl->id,
            'original_url' => 'https://www.tracken.com.br/pagina-nova',
            'short_code' => 'AAAAAA',
        ]);
    }

    public function test_can_delete_a_short_url(): void
    {
        $shortUrl = ShortUrl::create(['original_url' => 'https://www.tracken.com.br/pagina-a', 'short_code' => 'AAAAAA']);

        $response = $this->deleteJson("/api/short-urls/{$shortUrl->id}");

        $response->assertNoContent();
        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_destroy_returns_404_for_unknown_id(): void
    {
        $response = $this->deleteJson('/api/short-urls/999');

        $response->assertNotFound();
    }
}
