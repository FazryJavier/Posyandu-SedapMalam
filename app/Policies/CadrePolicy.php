<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Cadre;
use Illuminate\Auth\Access\HandlesAuthorization;

class CadrePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Cadre');
    }

    public function view(AuthUser $authUser, Cadre $cadre): bool
    {
        return $authUser->can('View:Cadre');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Cadre');
    }

    public function update(AuthUser $authUser, Cadre $cadre): bool
    {
        return $authUser->can('Update:Cadre');
    }

    public function delete(AuthUser $authUser, Cadre $cadre): bool
    {
        return $authUser->can('Delete:Cadre');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Cadre');
    }

    public function restore(AuthUser $authUser, Cadre $cadre): bool
    {
        return $authUser->can('Restore:Cadre');
    }

    public function forceDelete(AuthUser $authUser, Cadre $cadre): bool
    {
        return $authUser->can('ForceDelete:Cadre');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Cadre');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Cadre');
    }

    public function replicate(AuthUser $authUser, Cadre $cadre): bool
    {
        return $authUser->can('Replicate:Cadre');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Cadre');
    }

}