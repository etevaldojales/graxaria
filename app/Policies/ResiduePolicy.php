<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Residue;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResiduePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Residue');
    }

    public function view(AuthUser $authUser, Residue $residue): bool
    {
        return $authUser->can('View:Residue');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Residue');
    }

    public function update(AuthUser $authUser, Residue $residue): bool
    {
        return $authUser->can('Update:Residue');
    }

    public function delete(AuthUser $authUser, Residue $residue): bool
    {
        return $authUser->can('Delete:Residue');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Residue');
    }

    public function restore(AuthUser $authUser, Residue $residue): bool
    {
        return $authUser->can('Restore:Residue');
    }

    public function forceDelete(AuthUser $authUser, Residue $residue): bool
    {
        return $authUser->can('ForceDelete:Residue');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Residue');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Residue');
    }

    public function replicate(AuthUser $authUser, Residue $residue): bool
    {
        return $authUser->can('Replicate:Residue');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Residue');
    }

}