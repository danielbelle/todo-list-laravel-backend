<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('security')]
#[Group('inputsanitization')]

class InputSanitizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_xss_script_injection_is_sanitized()
    {
        $xssPayload = '<script>alert("XSS")</script>Task';
        $expectedSanitized = 'alert("XSS")Task';

        $response = $this->postJson('/api/v1/tasks', [
            'title' => $xssPayload,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('tasks', [
            'title' => $expectedSanitized
        ]);

        $task = \App\Models\Task::first();
        $this->assertStringNotContainsString('<script>', $task->title);
        $this->assertStringNotContainsString('</script>', $task->title);
    }

    public function test_sql_injection_attempts_are_handled_safely()
    {
        $sqlInjection = "1'; DROP TABLE tasks; --";

        $response = $this->getJson("/api/v1/tasks?search={$sqlInjection}");

        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);
    }

    public function test_various_xss_attempts_are_sanitized()
    {
        $testCases = [
            '<script>alert("XSS")</script>Task' => 'alert("XSS")Task',
            '<img src="x" onerror="alert(\'XSS\')">Task' => 'Task',
            '<div onclick="alert(\'XSS\')">Click me</div>' => 'Click me',
            '<a href="javascript:alert(\'XSS\')">Link</a>' => 'Link',
            'Normal text <script>bad</script> here' => 'Normal text bad here',
        ];

        foreach ($testCases as $input => $expected) {
            $response = $this->postJson('/api/v1/tasks', [
                'title' => $input,
            ]);

            $response->assertSuccessful();
            $this->assertDatabaseHas('tasks', [
                'title' => $expected
            ]);


            \App\Models\Task::truncate();
        }
    }

    public function test_html_tags_are_fully_removed()
    {
        $dangerousInput = '<script>alert("XSS")</script><img src="x" onerror="alert(\'XSS\')"><div onclick="alert(\'XSS\')">Click</div>';
        $expected = 'alert("XSS")Click';

        $response = $this->postJson('/api/v1/tasks', [
            'title' => $dangerousInput,
        ]);

        $response->assertSuccessful();

        $task = \App\Models\Task::first();

        $this->assertStringNotContainsString('<script>', $task->title);
        $this->assertStringNotContainsString('</script>', $task->title);
        $this->assertStringNotContainsString('<img', $task->title);
        $this->assertStringNotContainsString('onerror', $task->title);
        $this->assertStringNotContainsString('<div', $task->title);
        $this->assertStringNotContainsString('onclick', $task->title);

        $this->assertEquals($expected, $task->title);
    }
}
