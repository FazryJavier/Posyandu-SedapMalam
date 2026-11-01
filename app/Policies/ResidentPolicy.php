<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Resident;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResidentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Resident');
    }

    public function view(AuthUser $authUser, Resident $resident): bool
    {
        return $authUser->can('View:Resident');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Resident');
    }

    public function update(AuthUser $authUser, Resident $resident): bool
    {
        return $authUser->can('Update:Resident');
    }

    public function delete(AuthUser $authUser, Resident $resident): bool
    {
        return $authUser->can('Delete:Resident');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Resident');
    }

    public function restore(AuthUser $authUser, Resident $resident): bool
    {
        return $authUser->can('Restore:Resident');
    }

    public function forceDelete(AuthUser $authUser, Resident $resident): bool
    {
        return $authUser->can('ForceDelete:Resident');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Resident');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Resident');
    }

    public function replicate(AuthUser $authUser, Resident $resident): bool
    {
        return $authUser->can('Replicate:Resident');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Resident');
    }

}