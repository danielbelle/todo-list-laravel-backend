<?php

namespace Tests\Feature\Performance;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group feature
 * @group performance
 * @group taskperformance
 */


class TaskPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_performance_with_large_dataset(): void
    {
        Task::factory()->count(1000)->create();

        $start = microtime(true);
        $response = $this->getJson('/api/v1/tasks');
        $end = microtime(true);

        $response->assertStatus(200);
        $this->assertLessThan(2, $end - $start); // less than 2 seconds
    }

    public function test_performance_with_filters(): void
    {
        Task::factory()->count(500)->create(['completed' => true]);
        Task::factory()->count(500)->create(['completed' => false]);

        $start = microtime(true);
        $response = $this->getJson('/api/v1/tasks?completed=true&per_page=50');
        $end = microtime(true);

        $response->assertStatus(200);
        $this->assertLessThan(1, $end - $start); // less than 1 second
    }

    public function test_performance_with_complex_filters(): void
    {
        Task::factory()->count(500)->create(['completed' => true]);
        Task::factory()->count(500)->create(['completed' => false]);

        $start = microtime(true);
        $response = $this->getJson('/api/v1/tasks?completed=true&search=test&per_page=50');
        $end = microtime(true);

        $response->assertStatus(200);
        $this->assertLessThan(1, $end - $start);
    }
}
