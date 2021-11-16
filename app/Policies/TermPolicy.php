<?php

namespace App\Policies;

use App\Term;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermPolicy
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
        if ($user->can('view_term')) {
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
    public function view(User $user, Term $term)
    {
        if ($user->can('view_term')) {
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
        
        if ($user->can('add_term')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the terms.
     *
     * @param  \App\User  $user
     * @param  \App\Term  $terms
     * @return mixed
     */
    public function update(User $user, Term $term)
    {
        if ($user->can('add_term')) {
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
    public function delete(User $user, Term $term)
    {
        if ($user->can('delete_term')) {
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
    public function restore(User $user, Term $term)
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
    public function forceDelete(User $user, Term $term)
    {
        //
    }
}
