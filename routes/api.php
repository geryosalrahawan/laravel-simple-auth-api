<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/createpost', [PostsController::class, 'createPosts']);
    Route::get('/showall', [PostsController::class, 'showmine']);
    Route::get('/users/{id}/posts', [PostsController::class, 'showother']);
    Route::put('/users/posts/{id}', [PostsController::class, 'update']);
    Route::delete('/users/posts/{id}', [PostsController::class, 'destroy']);
    

});
Route::middleware(['auth:api', 'role:admin'])->put('/users/{id}/role', [AuthController::class, 'updateRole']);