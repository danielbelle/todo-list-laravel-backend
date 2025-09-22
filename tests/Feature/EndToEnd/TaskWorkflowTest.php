<?php

namespace Tests\Feature\EndToEnd;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('endtoend')]
#[Group('workflows')]

class TaskWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_task_workflow(): void
    {
        // 1. Create task
        $createResponse = $this->postJson('/api/v1/tasks', [
            'title' => 'End-to-End Test Task'
        ]);

        $createResponse->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'title', 'completed']])
            ->assertJson(['data' => ['title' => 'End-to-End Test Task', 'completed' => false]]);

        $taskId = $createResponse->json('data.id');

        // 2. Mark as complete
        $completeResponse = $this->patchJson("/api/v1/tasks/{$taskId}/complete");
        $completeResponse->assertStatus(200)
            ->assertJson(['data' => ['completed' => true]]);

        // 3. Verify it's complete
        $getResponse = $this->getJson("/api/v1/tasks/{$taskId}");
        $getResponse->assertStatus(200)
            ->assertJson(['data' => ['completed' => true, 'status' => 'completed']]);

        // 4. Mark as pending
        $pendingResponse = $this->patchJson("/api/v1/tasks/{$taskId}/pending");
        $pendingResponse->assertStatus(200)
            ->assertJson(['data' => ['completed' => false]]);

        // 5. Update title
        $updateResponse = $this->putJson("/api/v1/tasks/{$taskId}", [
            'title' => 'Updated End-to-End Task'
        ]);
        $updateResponse->assertStatus(200)
            ->assertJson(['data' => ['title' => 'Updated End-to-End Task']]);

        // 6. Delete
        $deleteResponse = $this->deleteJson("/api/v1/tasks/{$taskId}");
        $deleteResponse->assertStatus(204);

        // 7. Verify it was deleted
        $getDeletedResponse = $this->getJson("/api/v1/tasks/{$taskId}");
        $getDeletedResponse->assertStatus(404);
    }

    public function test_task_lifecycle_with_filters(): void
    {
        // Create multiple tasks with different states
        Task::factory()->create(['title' => 'Workflow Task 1', 'completed' => false]);
        Task::factory()->create(['title' => 'Workflow Task 2', 'completed' => true]);
        Task::factory()->create(['title' => 'Another Task', 'completed' => false]);

        // Test filters during the workflow
        $response = $this->getJson('/api/v1/tasks?completed=true');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Workflow Task 2']);

        $response = $this->getJson('/api/v1/tasks?search=Workflow');
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
}
