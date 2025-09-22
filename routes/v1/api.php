<?php

//use App\Http\Controllers\Api\V1\AuthController;
//use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for version 1 of your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group with the prefix "api/v1".
|
*/

// Authentication Routes - MVP will not use authentication for now
/*
Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');

        // Protected routes
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('me', [AuthController::class, 'me'])->name('me');
            Route::get('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users/active', [UserController::class, 'active'])->name('users.active');
    Route::get('users/all', [UserController::class, 'all'])->name('users.all');
    Route::apiResource('users', UserController::class)->names('users');
});
*/
// Task Routes with appropriate rate limiting
Route::prefix('tasks')->group(function () {
    // List tasks: 60 req/min
    Route::get('/', [TaskController::class, 'index'])->middleware('throttle:60,1');

    // Show task: 120 req/min 
    Route::get('/{id}', [TaskController::class, 'show'])->middleware('throttle:120,1');

    // Create task: 10 req/min
    Route::post('/', [TaskController::class, 'store'])->middleware('throttle:10,1');

    // Update task: 10 req/min
    Route::put('/{id}', [TaskController::class, 'update'])->middleware('throttle:10,1');

    // Delete task: 10 req/min
    Route::delete('/{id}', [TaskController::class, 'destroy'])->middleware('throttle:10,1');

    // Complete task: 20 req/min
    Route::patch('/{id}/complete', [TaskController::class, 'complete'])->middleware('throttle:20,1');

    // Mark as pending: 20 req/min
    Route::patch('/{id}/pending', [TaskController::class, 'pending'])->middleware('throttle:20,1');
});
