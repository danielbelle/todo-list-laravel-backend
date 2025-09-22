<?php

namespace Tests\Feature\EdgeCases;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group feature
 * @group edge-cases
 * @group taskpaginationedgecases
 */


class TaskPaginationEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagination_out_of_bounds(): void
    {
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/tasks?page=999');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }
}
