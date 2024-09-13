<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;

class MeetingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true; // Semua user bisa melihat daftar meeting
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Meeting $meeting)
    {
        return $user->id === $meeting->creator_id || $meeting->users->contains($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Hanya user non-admin yang bisa membuat meeting
        return !$user->isAdmin();
    }

    public function update(User $user, Meeting $meeting)
    {
        // Hanya pembuat meeting yang bisa mengupdate
        // Anda mungkin perlu menambahkan kolom creator_id ke tabel meetings
        return $user->id === $meeting->creator_id;
    }

    public function delete(User $user, Meeting $meeting)
    {
        // Hanya pembuat meeting yang bisa menghapus
        return $user->id === $meeting->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Meeting $meeting)
    {
        return $user->id === $meeting->creator_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Meeting $meeting)
    {
        return $user->id === $meeting->creator_id;
    }

    public function checkIn(User $user, Meeting $meeting)
    {
        // Hanya non-admin user yang merupakan bagian dari meeting yang bisa check in
        return !$user->isAdmin() && $meeting->users->contains($user);
    }
}
