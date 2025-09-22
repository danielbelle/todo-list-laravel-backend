<?php

namespace Tests\Feature\EdgeCases;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group feature
 * @group edge-cases
 * @group taskedgecases
 */


class TaskEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_database_scenario(): void
    {
        // Edge case: Empty database
        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 0,
                    'per_page' => 15,
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => null,
                    'to' => null
                ]
            ]);
    }

    public function test_max_per_page_scenario(): void
    {
        // Edge case: Maximum items per page
        Task::factory()->count(100)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=100');

        $response->assertStatus(200)
            ->assertJsonCount(100, 'data')
            ->assertJson([
                'meta' => [
                    'per_page' => 100,
                    'total' => 100,
                    'last_page' => 1
                ]
            ]);
    }

    public function test_large_search_string_scenario(): void
    {
        // Edge case: Very long search string
        $longSearch = str_repeat('a', 255);
        Task::factory()->create(['title' => 'Test task']);

        $response = $this->getJson('/api/v1/tasks?search=' . urlencode($longSearch));

        $response->assertStatus(200); // Should not break
    }

    public function test_special_characters_search(): void
    {
        // Edge case: Special characters in search
        Task::factory()->create(['title' => 'Task with % special & characters']);
        Task::factory()->create(['title' => 'Normal task']);

        $response = $this->getJson('/api/v1/tasks?search=% special &');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Task with % special & characters']);
    }

    public function test_pagination_out_of_bounds(): void
    {
        // Edge case: Page beyond the total
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/tasks?page=999');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'meta' => [
                    'current_page' => 999,
                    'last_page' => 1,
                    'total' => 5,
                    'from' => null,
                    'to' => null
                ]
            ]);
    }

    public function test_zero_per_page_scenario(): void
    {
        // Edge case: per_page=1 SQLite limits to minimum 1
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=0');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'per_page' => 1, // SQLite limits to minimum 1
                    'total' => 5
                ]
            ]);
    }

    public function test_negative_per_page_scenario(): void
    {
        // Edge case: negative per_page (should use default)
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=-1');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'per_page' => 1, // SQLite limits to minimum 1
                    'total' => 5
                ]
            ]);
    }

    public function test_very_large_per_page_scenario(): void
    {
        // Edge case: per_page larger than max (should cap at max, e.g., 100)
        Task::factory()->count(150)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=1000');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'per_page' => 100, // capped at 100
                    'total' => 150
                ]
            ]);
    }
}
