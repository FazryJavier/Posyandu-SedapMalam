<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Neighborhood;
use Illuminate\Auth\Access\HandlesAuthorization;

class NeighborhoodPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Neighborhood');
    }

    public function view(AuthUser $authUser, Neighborhood $neighborhood): bool
    {
        return $authUser->can('View:Neighborhood');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Neighborhood');
    }

    public function update(AuthUser $authUser, Neighborhood $neighborhood): bool
    {
        return $authUser->can('Update:Neighborhood');
    }

    public function delete(AuthUser $authUser, Neighborhood $neighborhood): bool
    {
        return $authUser->can('Delete:Neighborhood');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Neighborhood');
    }

    public function restore(AuthUser $authUser, Neighborhood $neighborhood): bool
    {
        return $authUser->can('Restore:Neighborhood');
    }

    public function forceDelete(AuthUser $authUser, Neighborhood $neighborhood): bool
    {
        return $authUser->can('ForceDelete:Neighborhood');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Neighborhood');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Neighborhood');
    }

    public function replicate(AuthUser $authUser, Neighborhood $neighborhood): bool
    {
        return $authUser->can('Replicate:Neighborhood');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Neighborhood');
    }

}