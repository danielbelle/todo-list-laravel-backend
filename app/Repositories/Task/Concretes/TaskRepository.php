<?php

namespace App\Repositories\Task\Concretes;

use App\Models\Task;
use App\Repositories\Task\Contracts\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Task::query();

        return $this->applyFilters($query, $filters)->paginate($perPage);
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $task = $this->find($id);

        if (!$task) {
            return false;
        }

        return $task->update($data);
    }

    public function delete(int $id): bool
    {
        $task = $this->find($id);

        if (!$task) {
            return false;
        }

        return $task->delete();
    }

    public function complete(int $id): bool
    {
        return $this->update($id, ['completed' => true]);
    }

    public function pending(int $id): bool
    {
        return $this->update($id, ['completed' => false]);
    }

    private function applyFilters($query, array $filters)
    {
        if (isset($filters['completed'])) {
            $query->where('completed', $filters['completed']);
        }

        if (isset($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['with_trashed']) && $filters['with_trashed']) {
            $query->withTrashed();
        }

        if (isset($filters['only_trashed']) && $filters['only_trashed']) {
            $query->onlyTrashed();
        }

        return $query;
    }
}
