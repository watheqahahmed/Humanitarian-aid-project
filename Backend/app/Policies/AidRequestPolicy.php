<?php

namespace App\Policies;

use App\Models\AidRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AidRequestPolicy
{
    /**
     * Determine whether the user can view any aid requests.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the specific aid request.
     */
    public function view(User $user, AidRequest $aidRequest): bool
    {
        return $user->role === 'admin' || $user->id === $aidRequest->beneficiary_id;
    }

    /**
     * Determine whether the user can create aid requests.
     */
    public function create(User $user): bool
    {
        return $user->role === 'beneficiary';
    }

    /**
     * Determine whether the user can update the aid request.
     */
    public function update(User $user, AidRequest $aidRequest): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the aid request.
     */
    public function delete(User $user, AidRequest $aidRequest): bool
    {
        return $user->role === 'admin';
    }
}
