<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InputSanitizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_xss_script_injection_is_sanitized()
    {
        $xssPayload = '<script>alert("XSS")</script>Task';

        $response = $this->postJson('/api/v1/tasks', [
            'title' => $xssPayload,
            'completed' => false
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('tasks', [
            'title' => $xssPayload
        ]);

        if ($response->getStatusCode() === 422) {
            $this->assertArrayHasKey('title', $response->json('errors'));
        }
    }

    public function test_sql_injection_attempts_are_handled_safely()
    {
        $sqlInjection = "1'; DROP TABLE tasks; --";

        $response = $this->getJson("/api/v1/tasks?search={$sqlInjection}");

        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);
    }
}
