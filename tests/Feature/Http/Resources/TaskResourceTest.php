<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\Api\Task\TaskResource;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('http')]
#[Group('resources')]


class TaskResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_transformation(): void
    {
        $task = Task::factory()->create([
            'title' => 'Test Task',
            'completed' => true
        ]);

        $resource = new TaskResource($task);
        $array = $resource->toArray(request());

        $this->assertEquals($task->id, $array['id']);
        $this->assertEquals('Test Task', $array['title']);
        $this->assertTrue($array['completed']);
        $this->assertEquals('completed', $array['status']);
        $this->assertNotNull($array['created_at']);
        $this->assertNotNull($array['updated_at']);
        $this->assertNull($array['deleted_at']);
        $this->assertFalse($array['is_deleted']);
    }

    public function test_resource_with_soft_deleted_task(): void
    {
        $task = Task::factory()->create();
        $task->delete();

        $resource = new TaskResource($task->fresh());
        $array = $resource->toArray(request());

        $this->assertNotNull($array['deleted_at']);
        $this->assertTrue($array['is_deleted']);
    }
}
