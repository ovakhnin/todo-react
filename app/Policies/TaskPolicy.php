<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any Tasks.
     */
    public function viewAny(User $user): bool
    {
        if ($user) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view Task.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can create Task.
     */
    public function create(User $user): bool
    {
        if ($user) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update Task.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can delete Task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can toggle 'completed' for the Task.
     */
    public function completed(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}
