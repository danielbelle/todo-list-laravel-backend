<?php

namespace Tests\Feature\Acceptance;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('acceptance')]

class TaskAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_creation_acceptance_criteria(): void
    {
        // Criteria: System must allow creating tasks with a valid title
        $response = $this->postJson('/api/v1/tasks', [
            'title' => 'New Task for Acceptance Testing'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'New Task for Acceptance Testing',
                    'completed' => false,
                    'status' => 'pending'
                ]
            ]);

        // Verify it persisted in the database
        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task for Acceptance Testing',
            'completed' => false
        ]);
    }

    public function test_task_filtering_acceptance_criteria(): void
    {
        // Criteria: System must allow filtering tasks by status
        Task::factory()->create(['title' => 'Completed Task', 'completed' => true]);
        Task::factory()->create(['title' => 'Pending Task', 'completed' => false]);
        Task::factory()->create(['title' => 'Another Completed', 'completed' => true]);

        // Filter by completed=true
        $responseCompleted = $this->getJson('/api/v1/tasks?completed=true');
        $responseCompleted->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Completed Task'])
            ->assertJsonFragment(['title' => 'Another Completed'])
            ->assertJsonMissing(['title' => 'Pending Task']);

        // Filter by completed=false
        $responsePending = $this->getJson('/api/v1/tasks?completed=false');
        $responsePending->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Pending Task'])
            ->assertJsonMissing(['title' => 'Completed Task']);
    }

    public function test_task_search_acceptance_criteria(): void
    {
        // Criteria: System must allow searching tasks by title
        Task::factory()->create(['title' => 'Find this specific task']);
        Task::factory()->create(['title' => 'Another task']);
        Task::factory()->create(['title' => 'Specific item to find']);

        $response = $this->getJson('/api/v1/tasks?search=specific');
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Find this specific task'])
            ->assertJsonFragment(['title' => 'Specific item to find'])
            ->assertJsonMissing(['title' => 'Another task']);
    }

    public function test_task_pagination_acceptance_criteria(): void
    {
        // Criteria: System must paginate results with meta information
        Task::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                    'from',
                    'to'
                ],
                'links'
            ])
            ->assertJson([
                'meta' => [
                    'total' => 25,
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 3
                ]
            ]);
    }

    public function test_task_status_workflow_acceptance(): void
    {
        // Criteria: System must allow changing the status of tasks
        $task = Task::factory()->create(['completed' => false]);

        // Complete task
        $completeResponse = $this->patchJson("/api/v1/tasks/{$task->id}/complete");
        $completeResponse->assertStatus(200)
            ->assertJson(['data' => ['completed' => true, 'status' => 'completed']]);

        // Revert to pending
        $pendingResponse = $this->patchJson("/api/v1/tasks/{$task->id}/pending");
        $pendingResponse->assertStatus(200)
            ->assertJson(['data' => ['completed' => false, 'status' => 'pending']]);
    }
}
