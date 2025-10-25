<?php

namespace App\Policies;

use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PipelineStagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PipelineStage $pipelineStage): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isGestor();
    }

    public function update(User $user, PipelineStage $pipelineStage): bool
    {
        return $user->isGestor();
    }

    public function delete(User $user, PipelineStage $pipelineStage): bool
    {
        return $user->isGestor();
    }
}
