<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('security')]
#[Group('ratelimiting')]

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_limiting_protects_against_abuse()
    {
        $maxAttempts = 60; // Should match the configured throttle

        for ($i = 1; $i <= $maxAttempts + 5; $i++) {
            $response = $this->getJson('/api/v1/tasks');

            if ($i > $maxAttempts) {
                // After the limit, should return 429
                $response->assertStatus(429);
                $response->assertJsonStructure(['message']);
            } else {
                // Within the limit, should be successful
                $this->assertTrue(
                    $response->isSuccessful(),
                    "Request $i should be successful but got status: " . $response->getStatusCode()
                );
            }
        }
    }

    public function test_different_endpoints_have_separate_rate_limits()
    {
        // Set cache driver to array
        config(['cache.default' => 'array']);

        // Create a task in the database so the show endpoint works
        $task = \App\Models\Task::factory()->create(['id' => 1]);

        $indexResponses = [];
        $showResponses = [];

        // Make 20 requests to each endpoint (within limits)
        for ($i = 0; $i < 20; $i++) {
            $indexResponses[] = $this->getJson('/api/v1/tasks'); // Limit: 60/min
            $showResponses[] = $this->getJson('/api/v1/tasks/1'); // Limit: 120/min
        }

        // Both should be OK (status 200)
        $this->assertEquals(
            200,
            $indexResponses[19]->getStatusCode(),
            'Index should work within limit. Status: ' . $indexResponses[19]->getStatusCode()
        );

        $this->assertEquals(
            200,
            $showResponses[19]->getStatusCode(),
            'Show should work within limit. Status: ' . $showResponses[19]->getStatusCode()
        );

        // Now saturate only the index endpoint
        $indexBlocked = false;
        for ($i = 0; $i < 50; $i++) {
            $response = $this->getJson('/api/v1/tasks');
            if ($response->getStatusCode() === 429) {
                $indexBlocked = true;
                break;
            }
        }

        // Even with index blocked, show should keep working
        $showResponse = $this->getJson('/api/v1/tasks/1');
        $this->assertEquals(
            200,
            $showResponse->getStatusCode(),
            'Show should still work even when index is blocked. Status: ' . $showResponse->getStatusCode()
        );

        $this->assertTrue($indexBlocked, 'Index should be rate limited after many requests');
    }
}
