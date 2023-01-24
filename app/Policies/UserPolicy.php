<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        if ($user->role->name == 'admin') {
            return true;
        }

        return $this->deny('Unauthorized');
    }

    public function show(User $user, User $model)
    {
        if ($user->role->name == 'admin' || $user->id == $model->id) {
            return true;
        }

        return $this->deny('Unauthorized');
    }

    public function update(User $user, User $model)
    {
        if ($user->role->name == 'admin' || $user->id == $model->id) {
            return true;
        }

        return $this->deny('Unauthorized');
    }

    public function destroy(User $user)
    {
        if ($user->role->name == 'admin') {
            return true;
        }

        return $this->deny('Unauthorized');
    }
}
