<?php

namespace App\Policies;

use App\ReasonReject;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReasonRejectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any terms.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->can('view_reason_reject')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the terms.
     *
     * @param  \App\User  $user
     * @param  \App\Term  $terms
     * @return mixed
     */
    public function view(User $user, ReasonReject $reasonReject)
    {
        if ($user->can('view_reason_reject')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create terms.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        
        if ($user->can('add_reason_reject')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the terms.
     *
     * @param  \App\User  $user
     * @param  \App\ReasonReject $reasonReject
     * @return mixed
     */
    public function update(User $user, ReasonReject $reasonReject)
    {
        
        if ($user->can('add_reason_reject')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the terms.
     *
     * @param  \App\User  $user
     * @param  \App\Term  $terms
     * @return mixed
     */
    public function delete(User $user, ReasonReject $reasonReject)
    {
        if ($user->can('delete_reason_reject')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the terms.
     *
     * @param  \App\User  $user
     * @param  \App\Term  $terms
     * @return mixed
     */
    public function restore(User $user, ReasonReject $reasonReject)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the terms.
     *
     * @param  \App\User  $user
     * @param  \App\Term  $terms
     * @return mixed
     */
    public function forceDelete(User $user, ReasonReject $term)
    {
        //
    }
}
