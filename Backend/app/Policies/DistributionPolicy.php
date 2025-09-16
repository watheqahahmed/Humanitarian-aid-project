<?php

namespace App\Policies;

use App\Models\Distribution;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DistributionPolicy
{
    /**
     * Determine whether the user can view any distributions.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'volunteer';
    }

    /**
     * Determine whether the user can view the specific distribution.
     */
    public function view(User $user, Distribution $distribution): bool
    {
        return $user->role === 'admin' || $user->id === $distribution->volunteer_id;
    }

    /**
     * Determine whether the user can update the distribution.
     */
    public function update(User $user, Distribution $distribution): bool
    {
        return $user->role === 'volunteer' && $user->id === $distribution->volunteer_id;
    }
}
