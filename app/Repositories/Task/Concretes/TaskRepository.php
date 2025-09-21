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
        $validPerPage = $this->validatePerPage($perPage);

        return $this->applyFilters($query, $filters)->paginate($validPerPage);
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
            $completed = filter_var($filters['completed'], FILTER_VALIDATE_BOOLEAN);
            $query->where('completed', $completed);
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

    private function validatePerPage(int $perPage): int
    {

        $minPerPage = 1;
        $maxPerPage = 100;

        if ($perPage < $minPerPage) {
            return $minPerPage;
        }

        if ($perPage > $maxPerPage) {
            return $maxPerPage;
        }

        return $perPage;
    }
}
