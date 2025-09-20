<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'completed', 'status', 'created_at']
                ],
                'meta' => ['total', 'per_page', 'current_page'],
                'links' => ['first', 'last', 'prev', 'next']
            ]);
    }

    public function test_index_with_filters(): void
    {
        Task::factory()->create(['title' => 'Find me', 'completed' => true]);
        Task::factory()->create(['title' => 'Another', 'completed' => false]);

        $response = $this->getJson('/api/v1/tasks?search=Find&completed=true');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Find me']);
    }

    public function test_show_existing_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'title', 'completed']])
            ->assertJsonFragment(['id' => $task->id]);
    }

    public function test_show_non_existing_task(): void
    {
        $response = $this->getJson('/api/v1/tasks/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Task not found']);
    }

    public function test_store_task(): void
    {
        $data = ['title' => 'New Task'];

        $response = $this->postJson('/api/v1/tasks', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'title', 'completed']])
            ->assertJsonFragment(['title' => 'New Task']);

        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_store_task_validation(): void
    {
        $response = $this->postJson('/api/v1/tasks', ['title' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_update_task(): void
    {
        $task = Task::factory()->create();
        $data = ['title' => 'Updated Task'];

        $response = $this->putJson("/api/v1/tasks/{$task->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Task']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task'
        ]);
    }

    public function test_update_non_existing_task(): void
    {
        $response = $this->putJson('/api/v1/tasks/999', ['title' => 'Updated']);

        $response->assertStatus(404);
    }

    public function test_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_delete_non_existing_task(): void
    {
        $response = $this->deleteJson('/api/v1/tasks/999');

        $response->assertStatus(404);
    }

    public function test_complete_task(): void
    {
        $task = Task::factory()->create(['completed' => false]);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete");

        $response->assertStatus(200)
            ->assertJsonFragment(['completed' => true]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true
        ]);
    }

    public function test_pending_task(): void
    {
        $task = Task::factory()->create(['completed' => true]);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/pending");

        $response->assertStatus(200)
            ->assertJsonFragment(['completed' => false]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => false
        ]);
    }

    public function test_complete_non_existing_task(): void
    {
        $response = $this->patchJson('/api/v1/tasks/999/complete');

        $response->assertStatus(404);
    }

    public function test_pending_non_existing_task(): void
    {
        $response = $this->patchJson('/api/v1/tasks/999/pending');

        $response->assertStatus(404);
    }
}
