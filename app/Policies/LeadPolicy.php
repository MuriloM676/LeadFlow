<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Lead $lead): bool
    {
        return $user->isGestor() || $lead->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Lead $lead): bool
    {
        return $user->isGestor() || $lead->user_id === $user->id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->isGestor();
    }

    public function restore(User $user, Lead $lead): bool
    {
        return $user->isGestor();
    }

    public function forceDelete(User $user, Lead $lead): bool
    {
        return $user->isGestor();
    }
}
