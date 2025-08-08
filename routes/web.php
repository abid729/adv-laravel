<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\TaskController;
use App\Jobs\GenerateReportJob;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
// use Throwable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

Route::get('/invite/{user}', function (App\Models\User $user) {
    $signedUrl = URL::signedRoute('accept-invite', ['user' => $user->id]);

    return $signedUrl;
});

Route::get('/accept-invite/{user}', function (App\Models\User $user) {
    return "Welcome {$user->name}, invitation accepted!";
})->name('accept-invite')->middleware('signed');

Route::get('/invite-temp/{user}', function (App\Models\User $user) {
    $signedUrl = URL::temporarySignedRoute(
        'accept-invite-temp',
        now()->addMinutes(30), // expires in 30 minutes
        ['user' => $user->id]
    );

    return $signedUrl;
});

Route::get('/accept-invite-temp/{user}', function (App\Models\User $user) {
    return "Welcome {$user->name}, temporary invitation accepted!";
})->name('accept-invite-temp')->middleware('signed');

Route::get('/download-report', function () {
    $url = Storage::disk('s3')->temporaryUrl(
        'reports/monthly.pdf',
        now()->addMinutes(5) // expires in 5 minutes
    );

    return $url;
});
Route::get('/batch-reports', function () {
    $batch = Bus::batch([
        new GenerateReportJob('January Report'),
        new GenerateReportJob('February Report'),
        new GenerateReportJob('March Report'),
    ])
    ->then(function (Batch $batch) {
        Log::info("All reports generated successfully!");
    })
   ->catch(function (Batch $batch, \Throwable $e) {
    Log::error("Batch failed: " . $e->getMessage());
})
    ->finally(function (Batch $batch) {
        Log::info("Batch process finished.");
    })
    ->dispatch();

    return "Batch ID: " . $batch->id;
});

Route::get('/generate-report', function () {
    GenerateReportJob::dispatch('Sales Report');
    return 'Report job dispatched!';
});
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
