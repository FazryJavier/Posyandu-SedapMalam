<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\GalleryItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:GalleryItem');
    }

    public function view(AuthUser $authUser, GalleryItem $galleryItem): bool
    {
        return $authUser->can('View:GalleryItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:GalleryItem');
    }

    public function update(AuthUser $authUser, GalleryItem $galleryItem): bool
    {
        return $authUser->can('Update:GalleryItem');
    }

    public function delete(AuthUser $authUser, GalleryItem $galleryItem): bool
    {
        return $authUser->can('Delete:GalleryItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:GalleryItem');
    }

    public function restore(AuthUser $authUser, GalleryItem $galleryItem): bool
    {
        return $authUser->can('Restore:GalleryItem');
    }

    public function forceDelete(AuthUser $authUser, GalleryItem $galleryItem): bool
    {
        return $authUser->can('ForceDelete:GalleryItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:GalleryItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:GalleryItem');
    }

    public function replicate(AuthUser $authUser, GalleryItem $galleryItem): bool
    {
        return $authUser->can('Replicate:GalleryItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:GalleryItem');
    }

}