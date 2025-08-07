<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Task::class => TaskPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Gate for admin dashboard access
        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->hasPermission('access-admin');
        });

        // Gate for team management
        Gate::define('manage-team', function (User $user, Team $team) {
            return $user->teams()->where('team_id', $team->id)
                ->where('role', 'leader')->exists();
        });

        // Gate for cross-team task viewing
        Gate::define('view-team-tasks', function (User $user, Team $team) {
            return $user->teams()->where('team_id', $team->id)->exists() ||
                $user->hasPermission('view-all-teams');
        });

        // Gate for task assignment
        Gate::define('assign-task', function (User $user, Task $task) {
            return $user->hasPermission('assign-tasks') ||
                ($task->team && $user->teams()->where('team_id', $task->team_id)
                    ->where('role', 'leader')->exists());
        });
    }
}