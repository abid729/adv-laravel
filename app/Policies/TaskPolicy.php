<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermission('view-tasks') || $user->hasPermission('view-all-tasks');
    }

    public function view(User $user, Task $task)
    {
        return $user->id === $task->user_id ||
            $user->hasPermission('view-all-tasks') ||
            ($task->team && $user->teams()->where('team_id', $task->team_id)->exists());
    }

    public function create(User $user)
    {
        return $user->hasPermission('create-tasks');
    }

    public function update(User $user, Task $task)
    {
        return $user->id === $task->user_id ||
            $user->hasPermission('edit-all-tasks') ||
            ($task->team && $user->teams()->where('team_id', $task->team_id)
                ->where('role', 'leader')->exists());
    }

    public function delete(User $user, Task $task)
    {
        return $user->hasPermission('delete-tasks') ||
            ($task->team && $user->teams()->where('team_id', $task->team_id)
                ->where('role', 'leader')->exists());
    }

    public function complete(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->hasPermission('complete-tasks');
    }
}
