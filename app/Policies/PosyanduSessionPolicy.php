<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PosyanduSession;
use Illuminate\Auth\Access\HandlesAuthorization;

class PosyanduSessionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PosyanduSession');
    }

    public function view(AuthUser $authUser, PosyanduSession $posyanduSession): bool
    {
        return $authUser->can('View:PosyanduSession');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PosyanduSession');
    }

    public function update(AuthUser $authUser, PosyanduSession $posyanduSession): bool
    {
        return $authUser->can('Update:PosyanduSession');
    }

    public function delete(AuthUser $authUser, PosyanduSession $posyanduSession): bool
    {
        return $authUser->can('Delete:PosyanduSession');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PosyanduSession');
    }

    public function restore(AuthUser $authUser, PosyanduSession $posyanduSession): bool
    {
        return $authUser->can('Restore:PosyanduSession');
    }

    public function forceDelete(AuthUser $authUser, PosyanduSession $posyanduSession): bool
    {
        return $authUser->can('ForceDelete:PosyanduSession');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PosyanduSession');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PosyanduSession');
    }

    public function replicate(AuthUser $authUser, PosyanduSession $posyanduSession): bool
    {
        return $authUser->can('Replicate:PosyanduSession');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PosyanduSession');
    }

}