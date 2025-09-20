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
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends BaseApiController
{
    public function __construct(
        private TaskServiceInterface $taskService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['completed', 'search']);
            $perPage = $request->get('per_page', 15);
            $tasks = $this->taskService->getAllTasks($filters, $perPage);

            return $this->successResponse(new TaskCollection($tasks));
        } catch (\Throwable $e) {
            Log::error('TaskController@index error', ['exception' => $e]);
            return $this->errorResponse('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            if (!$task) {
                return $this->notFoundResponse('Task not found');
            }

            return $this->successResponse(new TaskResource($task));
        } catch (\Throwable $e) {
            Log::error("TaskController@show error for id {$id}", ['exception' => $e]);
            return $this->errorResponse('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(TaskStoreRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            if (!$task) {
                Log::warning('TaskController@store: service returned null/false');
                return $this->errorResponse('Could not create task', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->createdResponse(new TaskResource($task));
        } catch (\Throwable $e) {
            Log::error('TaskController@store error', ['exception' => $e]);
            return $this->errorResponse('Could not create task', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function update(TaskUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->updateTask($id, $request->validated());
            if (!$task) {
                return $this->notFoundResponse('Task not found');
            }

            return $this->successResponse(new TaskResource($task));
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Task not found')) {
                return $this->notFoundResponse('Task not found');
            }
            Log::error("TaskController@update error for id {$id}", ['exception' => $e]);
            return $this->errorResponse('Could not update task', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->taskService->deleteTask($id);
            if (!$deleted) {
                return $this->notFoundResponse('Task not found');
            }

            return $this->noContentResponse();
        } catch (\Throwable $e) {
            Log::error("TaskController@destroy error for id {$id}", ['exception' => $e]);
            return $this->errorResponse('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function complete(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->completeTask($id);
            if (!$task) {
                return $this->notFoundResponse('Task not found');
            }

            return $this->successResponse(new TaskResource($task));
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Task not found')) {
                return $this->notFoundResponse('Task not found');
            }
            Log::error("TaskController@complete error for id {$id}", ['exception' => $e]);
            return $this->errorResponse('Could not complete task', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function pending(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->pendingTask($id);
            if (!$task) {
                return $this->notFoundResponse('Task not found');
            }

            return $this->successResponse(new TaskResource($task));
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Task not found')) {
                return $this->notFoundResponse('Task not found');
            }

            Log::error("TaskController@pending error for id {$id}", ['exception' => $e]);
            return $this->errorResponse('Could not mark task as pending', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
