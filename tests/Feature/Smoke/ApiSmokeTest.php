<?php

namespace Tests\Feature\Smoke;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

/**
 * @group feature
 */

class ApiSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_smoke_test(): void
    {
        // Basic API health check - main endpoint
        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => ['total', 'per_page', 'current_page', 'last_page'],
                'links' => ['first', 'last', 'prev', 'next']
            ])
            ->assertJson(['success' => true]);
    }

    public function test_crud_smoke_test(): void
    {
        // Quick full CRUD smoke test
        $createResponse = $this->postJson('/api/v1/tasks', ['title' => 'Smoke Test Task']);
        $createResponse->assertStatus(201);

        $taskId = $createResponse->json('data.id');

        // Read
        $readResponse = $this->getJson("/api/v1/tasks/{$taskId}");
        $readResponse->assertStatus(200)
            ->assertJson(['data' => ['title' => 'Smoke Test Task']]);

        // Update
        $updateResponse = $this->putJson("/api/v1/tasks/{$taskId}", [
            'title' => 'Updated Smoke Test Task'
        ]);
        $updateResponse->assertStatus(200)
            ->assertJson(['data' => ['title' => 'Updated Smoke Test Task']]);

        // Delete
        $deleteResponse = $this->deleteJson("/api/v1/tasks/{$taskId}");
        $deleteResponse->assertStatus(204);

        // Verify deletion
        $getDeletedResponse = $this->getJson("/api/v1/tasks/{$taskId}");
        $getDeletedResponse->assertStatus(404);
    }

    public function test_api_status_endpoints(): void
    {
        // Test status endpoints
        $completeTask = Task::factory()->create();
        $pendingTask = Task::factory()->create(['completed' => false]);

        // Complete endpoint
        $completeResponse = $this->patchJson("/api/v1/tasks/{$completeTask->id}/complete");
        $completeResponse->assertStatus(200);

        // Pending endpoint
        $pendingResponse = $this->patchJson("/api/v1/tasks/{$pendingTask->id}/pending");
        $pendingResponse->assertStatus(200);
    }
}
