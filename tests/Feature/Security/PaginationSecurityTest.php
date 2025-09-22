<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('security')]
#[Group('paginationsecurity')]

class PaginationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_per_page_parameter_validation()
    {
        $response = $this->getJson('/api/v1/tasks?per_page=10');
        $response->assertSuccessful();

        $response = $this->getJson('/api/v1/tasks?per_page=1000');
        $response->assertStatus(422);

        $response = $this->getJson('/api/v1/tasks?per_page=string');
        $response->assertStatus(422);

        $response = $this->getJson('/api/v1/tasks?per_page=1;DROP TABLE tasks;');
        $response->assertStatus(422);
    }

    public function test_per_page_has_maximum_limit()
    {
        $response = $this->getJson('/api/v1/tasks?per_page=500');

        if ($response->getStatusCode() === 422) {
            $response->assertJsonValidationErrors(['per_page']);
        } else {
            $data = $response->json();
            $this->assertLessThanOrEqual(100, count($data['data']));
        }
    }
}
