<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\TaskController;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        Gate::authorize('view-admin-dashboard');
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');
});
Route::get('/test-redis', function () {
    Cache::put('my_key', 'Hello from Redis!', now()->addMinutes(10));

    return Cache::get('my_key');
});
Route::get('/set-session', function () {
    session(['my_name' => 'Abid']);
    return 'Session set!';
});

Route::get('/get-session', function () {
    return session('my_name');
});

Route::get('/', function () {
    return view('welcome');
});



Route::get('/set-session', [UserController::class, 'setSession']);
Route::get('/get-session', [UserController::class, 'getSession']);

Route::get('/cache-users', [UserController::class, 'cacheUsers']);
Route::get('/clear-users-cache', [UserController::class, 'clearUsersCache']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
