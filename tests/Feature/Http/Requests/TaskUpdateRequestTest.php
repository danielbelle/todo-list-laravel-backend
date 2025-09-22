<?php

namespace Tests\Feature\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * @group feature
 * @group requests
 * @group taskupdaterequest
 */


class TaskUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_passes_with_valid_data(): void
    {
        $task = Task::factory()->create();
        $data = ['title' => 'Valid Task Title', 'completed' => true];

        $validator = Validator::make($data, (new \App\Http\Requests\Api\V1\TaskUpdateRequest())->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_update_with_invalid_data(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/v1/tasks/{$task->id}", [
            'title' => '',
            'completed' => 'not-a-boolean'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'completed']);
    }
}
