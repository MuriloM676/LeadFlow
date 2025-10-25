<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Activity $activity): bool
    {
        return $user->isGestor() || 
               $activity->user_id === $user->id || 
               $activity->lead->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Activity $activity): bool
    {
        return $user->isGestor() || $activity->user_id === $user->id;
    }

    public function delete(User $user, Activity $activity): bool
    {
        return $user->isGestor() || $activity->user_id === $user->id;
    }
}
