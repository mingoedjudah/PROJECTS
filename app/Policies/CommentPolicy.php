<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\UserRegistration;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UserRegistration $userRegistration): bool
    {
        // Implement your logic
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UserRegistration $userRegistration, Comment $comment): bool
    {
        // Implement your logic
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UserRegistration $userRegistration): bool
    {
        // Implement your logic
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UserRegistration $userRegistration, Comment $comment): bool
    {
        return $userRegistration->id === $comment->user_id;
    }
    
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UserRegistration $userRegistration, Comment $comment): bool
    {
        return $userRegistration->id === $comment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(UserRegistration $userRegistration, Comment $comment): bool
    {
        // Implement your logic
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(UserRegistration $userRegistration, Comment $comment): bool
    {
        // Implement your logic
    }
}
