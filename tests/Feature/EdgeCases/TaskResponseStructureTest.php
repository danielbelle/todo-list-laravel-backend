<?php

namespace Tests\Feature\EdgeCases;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskResponseStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_structure_consistency(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'completed',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'is_deleted'
                ]
            ],
            'meta' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'from',
                'to'
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ]
        ]);
    }
}
