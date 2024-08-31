<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Flashcards;
use Illuminate\Auth\Access\Response;

class FlashcardsPolicy
{

    public function modify(User $user, Flashcards $flashcards): Response
    {
        return $user->id == $flashcards ->user_id
        ? Response::allow():Response::deny('This is not your posts');
    }
}
