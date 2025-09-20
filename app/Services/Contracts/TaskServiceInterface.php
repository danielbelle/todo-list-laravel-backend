<?php

namespace App\Services\Contracts;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskServiceInterface
{
    public function getAllTasks(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getTaskById(int $id): ?Task;
    public function createTask(array $data): Task;
    public function updateTask(int $id, array $data): Task;
    public function deleteTask(int $id): bool;
    public function completeTask(int $id): Task;
    public function pendingTask(int $id): Task;
}
