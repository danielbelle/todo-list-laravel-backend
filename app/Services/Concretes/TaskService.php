<?php

namespace App\Services\Concretes;

use App\Models\Task;
use App\Repositories\Task\Contracts\TaskRepositoryInterface;
use App\Services\Contracts\TaskServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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
            throw new \Exception('Task not found or could not be updated');
        }

        return $this->getTaskById($id);
    }

    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }

    public function restoreTask(int $id): Task
    {
        if (!$this->taskRepository->restore($id)) {
            throw new \Exception('Task not found or could not be restored');
        }

        return $this->getTaskById($id);
    }

    public function forceDeleteTask(int $id): bool
    {
        return $this->taskRepository->forceDelete($id);
    }

    public function completeTask(int $id): Task
    {
        if (!$this->taskRepository->complete($id)) {
            throw new \Exception('Task not found or could not be completed');
        }

        return $this->getTaskById($id);
    }

    public function pendingTask(int $id): Task
    {
        if (!$this->taskRepository->pending($id)) {
            throw new \Exception('Task not found or could not be marked as pending');
        }

        return $this->getTaskById($id);
    }
}
