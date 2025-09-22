<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\Api\V1\TaskStoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('http')]
#[Group('requests')]


class TaskStoreRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_passes_with_valid_data(): void
    {
        $data = ['title' => 'Valid Task Title'];

        $validator = Validator::make($data, (new TaskStoreRequest())->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_validation_fails_without_title(): void
    {
        $data = [];

        $validator = Validator::make($data, (new TaskStoreRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_validation_fails_with_short_title(): void
    {
        $data = ['title' => 'ab'];

        $validator = Validator::make($data, (new TaskStoreRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_validation_fails_with_long_title(): void
    {
        $data = ['title' => str_repeat('a', 256)];

        $validator = Validator::make($data, (new TaskStoreRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_validation_fails_with_non_string_title(): void
    {
        $data = ['title' => 123];

        $validator = Validator::make($data, (new TaskStoreRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }
}
