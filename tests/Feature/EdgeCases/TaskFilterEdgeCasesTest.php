<?php

namespace Tests\Feature\EdgeCases;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group feature
 * @group edge-cases
 * @group taskfilteredgecases
 */


class TaskFilterEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_with_special_characters(): void
    {
        Task::factory()->create(['title' => 'Task with % special & characters']);

        $response = $this->getJson('/api/v1/tasks?search=% special &');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_filter_with_very_long_search(): void
    {
        $longSearch = str_repeat('a', 100);
        Task::factory()->create(['title' => 'Test task']);

        $response = $this->getJson('/api/v1/tasks?search=' . $longSearch);

        $response->assertStatus(200);
    }
}
