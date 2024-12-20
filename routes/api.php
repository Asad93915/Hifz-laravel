<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\AuthController;

// Public Routes (No authentication required)
Route::prefix('branches')->group(function () {
    Route::get('/', [BranchController::class, 'index']); // List all branches
    Route::get('{branch}', [BranchController::class, 'show']); // Show specific branch
    Route::post('/', [BranchController::class, 'store']); // Create a new branch
    Route::put('{branch}', [BranchController::class, 'update']); // Update a branch
    Route::delete('{branch}', [BranchController::class, 'destroy']); // Delete a branch
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // List all users
    Route::get('{user}', [UserController::class, 'show']); // Show specific user
    Route::post('/', [UserController::class, 'store']); // Create a new user (public)
    Route::put('{user}', [UserController::class, 'update']); // Update a user (authenticated)
    Route::delete('{user}', [UserController::class, 'destroy']); // Delete a user (authenticated)
});

// Login Route (Generates API Token)
Route::post('login', [AuthController::class, 'login']); // Public route for login

// Protected Routes (Requires authentication)
Route::middleware('auth:sanctum')->prefix('classes')->group(function () {
    Route::post('/', [ClassesController::class, 'store']);
    Route::get('/', [ClassesController::class, 'index']); // List all classes
    Route::get('/{classId}', [ClassesController::class, 'getTeacherForClass']);
    Route::put('/{classId}', [ClassesController::class, 'update']);
    Route::delete('/{classId}', [ClassesController::class, 'destroy']);
    Route::post('/remove-teacher', [ClassesController::class, 'removeTeacherFromClass']); // Delete a class
    Route::put('/classes/{classId}/assign-teacher', [ClassesController::class, 'assignTeacherToClass']);

});
