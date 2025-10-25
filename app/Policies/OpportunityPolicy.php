<?php

namespace App\Policies;

use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OpportunityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Opportunity $opportunity): bool
    {
        return $user->isGestor() || $opportunity->lead->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Opportunity $opportunity): bool
    {
        return $user->isGestor() || $opportunity->lead->user_id === $user->id;
    }

    public function delete(User $user, Opportunity $opportunity): bool
    {
        return $user->isGestor() || $opportunity->lead->user_id === $user->id;
    }
}
