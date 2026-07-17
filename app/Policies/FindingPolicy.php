<?php

namespace App\Policies;

use App\Models\Finding;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FindingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Finding $finding): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model (e.g. submit actions).
     */
    public function update(User $user, Finding $finding): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Manager') || $user->id === $finding->assigned_to;
    }

    /**
     * Determine whether the user can verify the finding (close it).
     */
    public function verify(User $user, Finding $finding): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Manager') || $user->id === $finding->created_by;
    }
}
