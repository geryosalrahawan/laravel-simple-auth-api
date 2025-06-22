<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/doctorregister', [AuthController::class, 'DoctorRegister']);
Route::post('/adminregister', [AuthController::class, 'Adminregister']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/posts', [PostsController::class, 'showall']);

// the following apis can be use by any registered user
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users/{id}/posts', [PostsController::class, 'showother']);
});
//the following apis only users with doctor role can use
Route::middleware(['auth:api', 'role:admin,doctor'])->group(function(){
    Route::post('/createpost', [PostsController::class, 'createPosts']);
    Route::get('/showmine', [PostsController::class, 'showmine']);
    Route::put('/users/posts/{id}', [PostsController::class, 'update']);
    Route::delete('/users/posts/{id}', [PostsController::class, 'destroy']);
});
//the following apis only admins can use
Route::middleware(['auth:api', 'role:admin'])->put('/users/{id}/role', [AuthController::class, 'updateRole']);