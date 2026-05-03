<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\User;

class AttendancePolicy
{
    /**
     * Determine whether the user can view attendance for a group.
     * During impersonation, the user must have exactly the same permissions as the impersonated user.
     */
    public function view(User $user, Group $group): bool
    {
        // Admin has full access to all groups without any checks
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teachers can view their own groups
        return $user->hasRole('teacher') && $group->teachers()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update attendance for a group.
     * During impersonation, the user must have exactly the same permissions as the impersonated user.
     */
    public function update(User $user, Group $group): bool
    {
        // Admin has full access to all groups without any checks
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teachers can update their own groups
        return $user->hasRole('teacher') && $group->teachers()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can view statistics for a group.
     */
    public function viewStatistics(User $user, Group $group): bool
    {
        return $this->view($user, $group);
    }

    /**
     * Determine whether the user can export attendance data.
     * During impersonation, the user must have exactly the same permissions as the impersonated user.
     */
    public function export(User $user, Group $group): bool
    {
        // Admin has full access to all groups without any checks
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teachers can export their own groups
        return $user->hasRole('teacher') && $group->teachers()->where('user_id', $user->id)->exists();
    }
}
