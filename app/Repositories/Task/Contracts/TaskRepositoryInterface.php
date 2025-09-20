<?php

namespace App\Repositories\Task\Contracts;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;


interface TaskRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Task;
    public function create(array $data): Task;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function complete(int $id): bool;
    public function pending(int $id): bool;
}
