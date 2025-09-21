<?php

namespace Tests\Unit\Services;

use App\Models\Task;
use App\Repositories\Task\Contracts\TaskRepositoryInterface;
use App\Services\Concretes\TaskService;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Mockery;

class TaskServiceTest extends TestCase
{
    private TaskService $service;
    private $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(TaskRepositoryInterface::class);
        $this->service = new TaskService($this->repositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_tasks(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 15);

        $this->repositoryMock->shouldReceive('all')
            ->with([], 15)
            ->once()
            ->andReturn($paginator);

        $result = $this->service->getAllTasks();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_get_task_by_id(): void
    {
        $task = Task::factory()->make();

        $this->repositoryMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($task);

        $result = $this->service->getTaskById(1);

        $this->assertInstanceOf(Task::class, $result);
    }

    public function test_create_task(): void
    {
        $task = Task::factory()->make();
        $data = ['title' => 'Test Task'];

        $this->repositoryMock->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($task);

        $result = $this->service->createTask($data);

        $this->assertInstanceOf(Task::class, $result);
    }

    public function test_update_task_success(): void
    {
        $task = Task::factory()->make();
        $data = ['title' => 'Updated Task'];

        $this->repositoryMock->shouldReceive('update')
            ->with(1, $data)
            ->once()
            ->andReturn(true);

        $this->repositoryMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($task);

        $result = $this->service->updateTask(1, $data);

        $this->assertInstanceOf(Task::class, $result);
    }

    public function test_update_task_failure(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Task not found or could not be updated');

        $this->repositoryMock->shouldReceive('update')
            ->with(1, ['title' => 'Updated'])
            ->once()
            ->andReturn(false);

        $this->service->updateTask(1, ['title' => 'Updated']);
    }

    public function test_delete_task(): void
    {
        $this->repositoryMock->shouldReceive('delete')
            ->with(1)
            ->once()
            ->andReturn(true);

        $result = $this->service->deleteTask(1);

        $this->assertTrue($result);
    }

    public function test_complete_task_success(): void
    {
        $task = Task::factory()->make();

        $this->repositoryMock->shouldReceive('complete')
            ->with(1)
            ->once()
            ->andReturn(true);

        $this->repositoryMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($task);

        $result = $this->service->completeTask(1);

        $this->assertInstanceOf(Task::class, $result);
    }

    public function test_complete_task_failure(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Task not found or could not be completed');

        $this->repositoryMock->shouldReceive('complete')
            ->with(1)
            ->once()
            ->andReturn(false);

        $this->service->completeTask(1);
    }

    public function test_pending_task_success(): void
    {
        $task = Task::factory()->make();

        $this->repositoryMock->shouldReceive('pending')
            ->with(1)
            ->once()
            ->andReturn(true);

        $this->repositoryMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($task);

        $result = $this->service->pendingTask(1);

        $this->assertInstanceOf(Task::class, $result);
    }

    public function test_pending_task_failure(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Task not found or could not be marked as pending');

        $this->repositoryMock->shouldReceive('pending')
            ->with(1)
            ->once()
            ->andReturn(false);

        $this->service->pendingTask(1);
    }

    public function test_get_all_tasks_with_filters(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 15);
        $filters = ['completed' => true, 'search' => 'test'];

        $this->repositoryMock->shouldReceive('all')
            ->with($filters, 15)
            ->once()
            ->andReturn($paginator);

        $result = $this->service->getAllTasks($filters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_get_all_tasks_with_custom_per_page(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 20);

        $this->repositoryMock->shouldReceive('all')
            ->with([], 20)
            ->once()
            ->andReturn($paginator);

        $result = $this->service->getAllTasks([], 20);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(20, $result->perPage());
    }

    public function test_get_all_tasks_with_empty_filters(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 15);

        $this->repositoryMock->shouldReceive('all')
            ->with([], 15)
            ->once()
            ->andReturn($paginator);

        $result = $this->service->getAllTasks([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}
