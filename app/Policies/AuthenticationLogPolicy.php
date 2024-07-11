<?php

namespace App\Policies;

use App\Models\User;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;
use Illuminate\Auth\Access\Response;

class AuthenticationLogPolicy
{

//    public function before(User $user, $ability, $leave){
//        if($user->is_admin) {
//            dd($ability, $user, $leave);
//            return true;
//        }
//    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AuthenticationLog $authenticationLog): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->is_admin;
    }
}
