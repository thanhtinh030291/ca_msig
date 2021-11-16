<?php

namespace App\Policies;

use App\User;
use App\Provider;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any claims.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        
        if ($user->can('view_provider')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the provider.
     *
     * @param  \App\User  $user
     * @param  \App\provider  $provider
     * @return mixed
     */
    public function view(User $user, Provider $provider)
    {
        if ($user->can('view_provider')) {
            return true;
        }
        
    }

    /**
     * Determine whether the user can create claims.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('add_provider')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the claim.
     *
     * @param  \App\User  $user
     * @param  \App\Claim  $claim
     * @return mixed
     */
    public function update(User $user, Provider $provider)
    {
        if ($user->can('edit_provider')) {
            return true;
        }
        return $user->id === $provider->created_user;
    }

    /**
     * Determine whether the user can delete the claim.
     *
     * @param  \App\User  $user
     * @param  \App\Claim  $claim
     * @return mixed
     */
    public function delete(User $user, Provider $provider)
    {
        if ($user->can('delete_claim')) {
            return true;
        }
        return $user->id === $provider->created_user;
    }

    /**
     * Determine whether the user can restore the claim.
     *
     * @param  \App\User  $user
     * @param  \App\Claim  $claim
     * @return mixed
     */
    public function restore(User $user, Provider $provider)
    {
        
    }

    /**
     * Determine whether the user can permanently delete the claim.
     *
     * @param  \App\User  $user
     * @param  \App\Claim  $claim
     * @return mixed
     */
    public function forceDelete(User $user, Provider $provider)
    {
        
    }
}
