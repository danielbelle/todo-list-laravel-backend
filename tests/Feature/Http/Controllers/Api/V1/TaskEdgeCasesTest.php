<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_already_completed_task(): void
    {
        $task = Task::factory()->create(['completed' => true]);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete");

        $response->assertStatus(200)
            ->assertJsonFragment(['completed' => true]);
    }

    public function test_pending_already_pending_task(): void
    {
        $task = Task::factory()->create(['completed' => false]);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/pending");

        $response->assertStatus(200)
            ->assertJsonFragment(['completed' => false]);
    }

    public function test_update_task_with_same_data(): void
    {
        $task = Task::factory()->create(['title' => 'Original Title']);

        $response = $this->putJson("/api/v1/tasks/{$task->id}", ['title' => 'Original Title']);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Original Title']);
    }
}
