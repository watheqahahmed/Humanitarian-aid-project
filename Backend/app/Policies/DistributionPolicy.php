<?php

namespace App\Policies;

use App\Models\Distribution;
use App\Models\User;

class DistributionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'volunteer';
    }

    public function view(User $user, Distribution $distribution): bool
    {
        return $user->role === 'admin' || $user->id === $distribution->volunteer_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin'; // فقط المسؤول يمكنه إنشاء توزيع
    }

    public function update(User $user, Distribution $distribution): bool
    {
        return $user->role === 'volunteer' && $user->id === $distribution->volunteer_id;
    }

    public function delete(User $user, Distribution $distribution): bool
    {
        return $user->role === 'admin';
    }
}
