<?php

namespace Tests\Feature\EdgeCases;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('edge_cases')]
#[Group('soft_delete')]



class TaskSoftDeleteTest extends TestCase
{
    use RefreshDatabase;


    public function test_soft_deleted_tasks_are_hidden_by_default(): void
    {
        $task = Task::factory()->create();
        $task->delete();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJson(['meta' => ['total' => 0]]);
    }

    public function test_soft_deleted_tasks_filter_not_implemented(): void
    {
        $task = Task::factory()->create();
        $task->delete();

        $response = $this->getJson('/api/v1/tasks?with_trashed=true');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJson(['meta' => ['total' => 0]]);
    }
}
