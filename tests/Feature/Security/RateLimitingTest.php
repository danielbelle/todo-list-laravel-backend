<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_limiting_protects_against_abuse()
    {
        $maxAttempts = 60;

        for ($i = 1; $i <= $maxAttempts + 5; $i++) {
            $response = $this->getJson('/api/v1/tasks');

            if ($i > $maxAttempts) {
                $response->assertStatus(429);
                $response->assertJsonStructure(['message']);
            } else {
                $response->assertSuccessful();
            }
        }
    }

    public function test_different_endpoints_have_separate_rate_limits()
    {
        $tasksResponses = [];
        $singleTaskResponses = [];

        for ($i = 0; $i < 30; $i++) {
            $tasksResponses[] = $this->getJson('/api/v1/tasks');
            $singleTaskResponses[] = $this->getJson('/api/v1/tasks/1');
        }

        $this->assertTrue($tasksResponses[29]->isSuccessful());
        $this->assertTrue($singleTaskResponses[29]->isSuccessful());
    }
}
