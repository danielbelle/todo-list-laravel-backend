<?php

namespace App\Services\Concretes;

use App\Models\Task;
use App\Repositories\Task\Contracts\TaskRepositoryInterface;
use App\Services\Contracts\TaskServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskService implements TaskServiceInterface
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function getAllTasks(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->taskRepository->all($filters, $perPage);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    public function createTask(array $data): Task
    {
        return $this->taskRepository->create($data);
    }

    public function updateTask(int $id, array $data): Task
    {
        if (!$this->taskRepository->update($id, $data)) {
            throw new ModelNotFoundException('Task not found');
        }

        return $this->getTaskById($id);
    }

    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }

    public function completeTask(int $id): Task
    {
        if (!$this->taskRepository->complete($id)) {
            throw new ModelNotFoundException('Task not found');
        }

        return $this->getTaskById($id);
    }

    public function pendingTask(int $id): Task
    {
        if (!$this->taskRepository->pending($id)) {
            throw new ModelNotFoundException('Task not found');
        }

        return $this->getTaskById($id);
    }
}
