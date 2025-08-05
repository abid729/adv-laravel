<?php

namespace App\Http\Controllers;
use App\Jobs\SendWelcomeEmail;
use App\Events\UserRegistered;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class UserController extends Controller
{
    // ✅ Use session to store data
    public function setSession(Request $request)
{
         $user = User::create([
        'name' => 'tessst',
        'email' => 'testsss@gmail.com',
        'password' => bcrypt('Test@124'),
    ]);
        // dispatch(new SendWelcomeEmail());
event(new UserRegistered($user));

        session(['user_name' => 'Abid Laravel']);
        return 'Session "user_name" has been set.';
    }

    // ✅ Retrieve session data
    public function getSession()
    {
        $name = session('user_name', 'No name in session');
        return 'Session value: ' . $name;
    }

    // ✅ Cache example: get users with caching
    public function cacheUsers()
    {
        $users = Cache::remember('users.all', 60, function () {
            return User::select('id', 'name', 'email')->take(10)->get();
        });

        return response()->json([
            'source' => 'redis cache',
            'data' => $users
        ]);
    }

    // ✅ Clear users cache
    public function clearUsersCache()
    {
        Cache::forget('users.all');
        return 'User cache cleared!';
    }
}

