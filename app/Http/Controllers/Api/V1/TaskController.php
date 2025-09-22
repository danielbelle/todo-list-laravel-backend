<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\TaskStoreRequest;
use App\Http\Requests\Api\V1\TaskUpdateRequest;
use App\Http\Resources\Api\Task\TaskResource;
use App\Http\Resources\Api\Task\TaskCollection;
use App\Services\Contracts\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends BaseApiController
{
    public function __construct(
        private TaskServiceInterface $taskService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['completed', 'search']);
        $perPage = $request->has('per_page')
            ? (int)$request->get('per_page')
            : 15;
        $tasks = $this->taskService->getAllTasks($filters, $perPage);

        return $this->successResponse(new TaskCollection($tasks));
    }

    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        if (!$task) {
            return $this->notFoundResponse('Task not found');
        }

        return $this->successResponse(new TaskResource($task));
    }


    public function store(TaskStoreRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());
        if (!$task) {
            return $this->validationErrorResponse('Validation failed');
        }

        return $this->createdResponse(new TaskResource($task));
    }

    public function update(TaskUpdateRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->updateTask($id, $request->validated());
        if (!$task) {
            return $this->notFoundResponse('Task not found');
        }

        return $this->successResponse(new TaskResource($task));
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->taskService->deleteTask($id);
        if (!$deleted) {
            return $this->notFoundResponse('Task not found');
        }

        return $this->noContentResponse();
    }

    public function complete(int $id): JsonResponse
    {
        $task = $this->taskService->completeTask($id);
        if (!$task) {
            return $this->notFoundResponse('Task not found');
        }

        return $this->successResponse(new TaskResource($task));
    }

    public function pending(int $id): JsonResponse
    {
        $task = $this->taskService->pendingTask($id);
        if (!$task) {
            return $this->notFoundResponse('Task not found');
        }

        return $this->successResponse(new TaskResource($task));
    }
}
