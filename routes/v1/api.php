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

// Authentication Routes MVP will not use authentication for now
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
// Task Routes
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
    Route::patch('/{id}/complete', [TaskController::class, 'complete']);
    Route::patch('/{id}/pending', [TaskController::class, 'pending']);
});
