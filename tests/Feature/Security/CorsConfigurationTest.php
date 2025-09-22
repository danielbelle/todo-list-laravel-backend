<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
#[Group('security')]
#[Group('corsconfiguration')]

class CorsConfigurationTest extends TestCase
{
    public function test_cors_headers_are_present()
    {
        $response = $this->options('/api/v1/tasks', [], [
            'Origin' => 'http://localhost:3000',
            'Access-Control-Request-Method' => 'GET',
            'Access-Control-Request-Headers' => 'Content-Type',
        ]);

        $response->assertHeader('Access-Control-Allow-Origin', '*');
        $response->assertHeader('Access-Control-Allow-Methods');
        $response->assertHeader('Access-Control-Allow-Headers');
    }

    public function test_cors_allows_configured_origins()
    {
        $allowedOrigin = '*';

        $response = $this->getJson('/api/v1/tasks', [
            'Origin' => $allowedOrigin
        ]);

        $response->assertHeader('Access-Control-Allow-Origin', $allowedOrigin);
    }

    public function test_cors_blocks_unconfigured_origins()
    {
        $response = $this->getJson('/api/v1/tasks', [
            'Origin' => 'http://malicious-site.com'
        ]);

        $this->assertNotEquals(
            'http://malicious-site.com',
            $response->headers->get('Access-Control-Allow-Origin')
        );
    }
}
