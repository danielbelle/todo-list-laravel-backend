<?php

namespace Tests\Feature\Concurrency;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('concurrency')]


class TaskConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_requests(): void
    {
        Task::factory()->count(100)->create();

        // Emule 10 concurrent requests
        $responses = [];
        for ($i = 0; $i < 10; $i++) {
            $responses[] = $this->getJson('/api/v1/tasks');
        }


        foreach ($responses as $response) {
            $response->assertStatus(200);
        }
    }
}
