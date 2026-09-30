<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Every active staff account may manage users, but nobody can lock
 * themselves out by deactivating or deleting their own account.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isActive();
    }

    public function create(User $user): bool
    {
        return $user->isActive();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isActive();
    }

    public function toggle(User $user, User $model): Response
    {
        return $user->is($model)
            ? Response::deny('You cannot deactivate your own account.')
            : Response::allow();
    }

    public function delete(User $user, User $model): Response
    {
        if ($user->is($model)) {
            return Response::deny('You cannot delete your own account.');
        }

        if (User::active()->count() <= 1 && $model->isActive()) {
            return Response::deny('At least one active administrator must remain.');
        }

        return Response::allow();
    }
}
