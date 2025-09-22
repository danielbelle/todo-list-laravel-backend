<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use App\Models\Task;

#[Group('feature')]
#[Group('security')]
#[Group('paginationsecurity')]

class PaginationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_per_page_parameter_validation()
    {
        \App\Models\Task::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=10');
        $response->assertSuccessful();

        $response = $this->getJson('/api/v1/tasks?per_page=1000');
        $response->assertSuccessful();

        $response = $this->getJson('/api/v1/tasks?per_page=string');
        $this->assertContains($response->getStatusCode(), [200, 422]);

        $response = $this->getJson('/api/v1/tasks?per_page=-5');
        $response->assertSuccessful();
    }

    public function test_per_page_has_maximum_limit()
    {
        \App\Models\Task::factory()->count(150)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=500');

        $response->assertSuccessful();

        $response->assertJsonCount(100, 'data');
        $response->assertJsonPath('meta.per_page', 100);
    }
}
