<?php

namespace Tests\Unit\Repositories\Task;

use App\Models\Task;
use App\Repositories\Task\Concretes\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TaskRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TaskRepository();
    }

    public function test_all_returns_paginated_tasks(): void
    {
        Task::factory()->count(20)->create();

        $result = $this->repository->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(20, $result->total());
    }

    public function test_all_with_filters(): void
    {
        Task::factory()->count(5)->create(['completed' => true]);
        Task::factory()->count(3)->create(['completed' => false]);

        $result = $this->repository->all(['completed' => true]);

        $this->assertEquals(5, $result->total());
        $this->assertTrue($result->first()->completed);
    }

    public function test_find_existing_task(): void
    {
        $task = Task::factory()->create();

        $result = $this->repository->find($task->id);

        $this->assertNotNull($result);
        $this->assertEquals($task->id, $result->id);
    }

    public function test_find_non_existing_task(): void
    {
        $result = $this->repository->find(999);

        $this->assertNull($result);
    }

    public function test_create_task(): void
    {
        $data = ['title' => 'Test Task', 'completed' => false];

        $result = $this->repository->create($data);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('Test Task', $result->title);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_update_existing_task(): void
    {
        $task = Task::factory()->create();
        $data = ['title' => 'Updated Task'];

        $result = $this->repository->update($task->id, $data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task'
        ]);
    }

    public function test_update_non_existing_task(): void
    {
        $result = $this->repository->update(999, ['title' => 'Updated']);

        $this->assertFalse($result);
    }

    public function test_delete_existing_task(): void
    {
        $task = Task::factory()->create();

        $result = $this->repository->delete($task->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_delete_non_existing_task(): void
    {
        $result = $this->repository->delete(999);

        $this->assertFalse($result);
    }

    public function test_complete_task(): void
    {
        $task = Task::factory()->create(['completed' => false]);

        $result = $this->repository->complete($task->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true
        ]);
    }

    public function test_pending_task(): void
    {
        $task = Task::factory()->create(['completed' => true]);

        $result = $this->repository->pending($task->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => false
        ]);
    }

    public function test_search_filter(): void
    {
        Task::factory()->create(['title' => 'Find this task']);
        Task::factory()->create(['title' => 'Another task']);

        $result = $this->repository->all(['search' => 'Find this']);

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Find this task', $result->first()->title);
    }
}
