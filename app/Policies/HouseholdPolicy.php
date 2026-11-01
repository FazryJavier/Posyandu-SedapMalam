<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Household;
use Illuminate\Auth\Access\HandlesAuthorization;

class HouseholdPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Household');
    }

    public function view(AuthUser $authUser, Household $household): bool
    {
        return $authUser->can('View:Household');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Household');
    }

    public function update(AuthUser $authUser, Household $household): bool
    {
        return $authUser->can('Update:Household');
    }

    public function delete(AuthUser $authUser, Household $household): bool
    {
        return $authUser->can('Delete:Household');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Household');
    }

    public function restore(AuthUser $authUser, Household $household): bool
    {
        return $authUser->can('Restore:Household');
    }

    public function forceDelete(AuthUser $authUser, Household $household): bool
    {
        return $authUser->can('ForceDelete:Household');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Household');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Household');
    }

    public function replicate(AuthUser $authUser, Household $household): bool
    {
        return $authUser->can('Replicate:Household');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Household');
    }

}