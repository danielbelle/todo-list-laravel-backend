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

/**
 * @group Tasks
 *
 * API for managing tasks
 *
 * This API provides complete CRUD operations for task management with filtering,
 * pagination, and status updates. No authentication is required.
 */
class TaskController extends BaseApiController
{
    public function __construct(
        private TaskServiceInterface $taskService
    ) {}

    /**
     * List all tasks
     *
     * Get a paginated list of tasks with optional filtering capabilities.
     *
     * @queryParam page integer Page number for pagination. Example: 1
     * @queryParam per_page integer Number of items per page (1-100). Example: 15
     * @queryParam completed boolean Filter by completion status. Example: true
     * @queryParam search string Search term to filter tasks by title. Example: important
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Complete project documentation",
     *       "completed": true,
     *       "status": "completed",
     *       "created_at": "2025-09-21T10:00:00.000000Z",
     *       "updated_at": "2025-09-21T10:30:00.000000Z",
     *       "deleted_at": null,
     *       "is_deleted": false
     *     }
     *   ],
     *   "meta": {
     *     "total": 25,
     *     "per_page": 15,
     *     "current_page": 1,
     *     "last_page": 2,
     *     "from": 1,
     *     "to": 15
     *   },
     *   "links": {
     *     "first": "http://localhost/api/v1/tasks?page=1",
     *     "last": "http://localhost/api/v1/tasks?page=2",
     *     "prev": null,
     *     "next": "http://localhost/api/v1/tasks?page=2"
     *   }
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Internal server error"
     * }
     */
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

    /**
     * Get a specific task
     *
     * Retrieve details of a single task by its ID.
     *
     * @urlParam id integer required The ID of the task. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "title": "Complete project documentation",
     *     "completed": true,
     *     "status": "completed",
     *     "created_at": "2025-09-21T10:00:00.000000Z",
     *     "updated_at": "2025-09-21T10:30:00.000000Z",
     *     "deleted_at": null,
     *     "is_deleted": false
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found"
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Internal server error"
     * }
     */
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

    /**
     * Create a new task
     *
     * Create a new task with the provided title.
     *
     * @bodyParam title string required The title of the task. Must be at least 3 characters. Example: Learn Laravel
     *
     * @response 201 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "title": "Learn Laravel",
     *     "completed": false,
     *     "status": "pending",
     *     "created_at": "2025-09-21T10:00:00.000000Z",
     *     "updated_at": "2025-09-21T10:00:00.000000Z",
     *     "deleted_at": null,
     *     "is_deleted": false
     *   }
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "title": [
     *       "The title field is required.",
     *       "The title must be at least 3 characters."
     *     ]
     *   }
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Could not create task"
     * }
     */
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

    /**
     * Update a task
     *
     * Update an existing task's title and/or completion status.
     *
     * @urlParam id integer required The ID of the task to update. Example: 1
     * @bodyParam title string The new title of the task. Must be at least 3 characters. Example: Updated task title
     * @bodyParam completed boolean The completion status of the task. Example: true
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "title": "Updated task title",
     *     "completed": true,
     *     "status": "completed",
     *     "created_at": "2025-09-21T10:00:00.000000Z",
     *     "updated_at": "2025-09-21T10:35:00.000000Z",
     *     "deleted_at": null,
     *     "is_deleted": false
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found"
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "title": [
     *       "The title must be at least 3 characters."
     *     ]
     *   }
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Could not update task"
     * }
     */
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

    /**
     * Delete a task
     *
     * Soft delete a task (can be restored if needed).
     *
     * @urlParam id integer required The ID of the task to delete. Example: 1
     *
     * @response 204 {}
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found"
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Internal server error"
     * }
     */
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

    /**
     * Mark task as complete
     *
     * Mark a specific task as completed.
     *
     * @urlParam id integer required The ID of the task to mark as complete. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "title": "Complete project documentation",
     *     "completed": true,
     *     "status": "completed",
     *     "created_at": "2025-09-21T10:00:00.000000Z",
     *     "updated_at": "2025-09-21T10:40:00.000000Z",
     *     "deleted_at": null,
     *     "is_deleted": false
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found"
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Could not complete task"
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Could not complete task"
     * }
     */
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

    /**
     * Mark task as pending
     *
     * Mark a specific task as pending (not completed).
     *
     * @urlParam id integer required The ID of the task to mark as pending. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "title": "Complete project documentation",
     *     "completed": false,
     *     "status": "pending",
     *     "created_at": "2025-09-21T10:00:00.000000Z",
     *     "updated_at": "2025-09-21T10:45:00.000000Z",
     *     "deleted_at": null,
     *     "is_deleted": false
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Task not found"
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Could not mark task as pending"
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Could not mark task as pending"
     * }
     */
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
