<?php

namespace Tests\Feature\Security;

use Tests\TestCase;

/**
 * @group feature
 * @group security
 * @group errorhandling
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ErrorHandlingTest extends TestCase
{
    public function test_production_environment_hides_sensitive_errors()
    {
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
        ], [
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Invalid JSON']);
    }
}
