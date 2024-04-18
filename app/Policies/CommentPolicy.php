<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    public function restore(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }
}
