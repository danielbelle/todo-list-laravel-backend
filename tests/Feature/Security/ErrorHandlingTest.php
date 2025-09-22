<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;

#[Group('feature')]
#[Group('security')]
#[Group('errorhandling')]

class ErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_environment_hides_sensitive_errors()
    {

        Task::factory()->count(3)->create();

        config(['app.env' => 'production']);
        config(['app.debug' => false]);

        $response = $this->getJson('/api/v1/tasks/999999');

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);

        $response->assertJsonMissingPath('exception');
        $response->assertJsonMissingPath('file');
        $response->assertJsonMissingPath('line');

        config(['app.env' => 'testing']);
    }

    public function test_invalid_json_returns_proper_error()
    {

        $response = $this->postJson('/api/v1/tasks', [
            'invalid' => "\xB1\x31"
        ]);

        $this->assertNotEquals(500, $response->getStatusCode());

        $this->assertTrue($response->getStatusCode() >= 400 && $response->getStatusCode() < 500);

        $response->assertJsonStructure(['message']);
    }
}
