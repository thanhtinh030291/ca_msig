<?php

namespace App\Policies;

use App\LetterTemplate;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterTemplatePolicy
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
        if ($user->can('view_letter_template')) {
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
    public function view(User $user, LetterTemplate $letterTemplate)
    {
        if ($user->can('view_letter_template')) {
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
        
        if ($user->can('add_letter_template')) {
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
    public function update(User $user, LetterTemplate $letterTemplate)
    {
        if ($user->can('add_letter_template')) {
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
    public function delete(User $user, LetterTemplate $letterTemplate)
    {
        if ($user->can('delete_letter_template')) {
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
    public function restore(User $user, LetterTemplate $letterTemplate)
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
    public function forceDelete(User $user, LetterTemplate $letterTemplate)
    {
        //
    }
}
