<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CommentController;

Route::prefix('fw/auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('signin', [AuthController::class, 'signin']);

    // Route yang butuh login
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('signout', [AuthController::class, 'signout']);
    });
});

Route::middleware('auth:sanctum')->prefix('fw/admin/user')->group(function () {
    Route::get('/', [UserController::class, 'index']);           // 1. Get All
    Route::post('add', [UserController::class, 'store']);        // 2. Add
    Route::put('edit/{id}', [UserController::class, 'update']);  // 3. Edit
    Route::delete('delete/{id}', [UserController::class, 'destroy']); // 4. Delete
});

Route::middleware('auth:sanctum')->prefix('fw/admin/employee')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);
    Route::post('add', [EmployeeController::class, 'store']);
    Route::put('edit/{id}', [EmployeeController::class, 'update']);
    Route::delete('delete/{id}', [EmployeeController::class, 'destroy']);
    Route::get('votes', [EmployeeController::class, 'countVotes']);
});

Route::middleware('auth:sanctum')->prefix('fw/employee')->group(function () {
    // Fitur Vote
    Route::post('vote/{id}', [VoteController::class, 'castVote']);
    Route::post('unvote/{id}', [VoteController::class, 'unvote']);
    Route::post('comment', [CommentController::class, 'store']);
});
