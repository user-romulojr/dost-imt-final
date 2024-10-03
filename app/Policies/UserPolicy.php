<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function library(User $user): bool
    {
        return $user->access_level_id == User::ROLE_SA;
    }

    public function submit(User $user): bool
    {
        return in_array($user->access_level_id, [ User::ROLE_SA, User::ROLE_AF ]);
    }

    public function approve(User $user): bool
    {
        return in_array($user->access_level_id, [User::ROLE_EXEC, User::ROLE_PD, User::ROLE_AH]);
    }
}
