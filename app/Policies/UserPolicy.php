<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  User  $authUser
     * @return bool
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    /**
     * Determine whether the user can view a specific user.
     *
     * @param  User  $authUser
     * @param  User  $user
     * @return bool
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id || $authUser->isAdmin();
    }

    /**
     * Determine whether the user can update a specific user.
     *
     * @param  User  $authUser
     * @param  User  $user
     * @return bool
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id || $authUser->isAdmin();
    }

    /**
     * Determine whether the user can delete a specific user.
     *
     * @param  User  $authUser
     * @param  User  $user
     * @return bool
     */
    public function delete(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }

    /**
     * Determine whether the user can restore a specific user.
     *
     * @param  User  $authUser
     * @param  User  $user
     * @return bool
     */
    public function restore(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete a specific user.
     *
     * @param  User  $authUser
     * @param  User  $user
     * @return bool
     */
    public function forceDelete(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }
}
