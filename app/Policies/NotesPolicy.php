<?php

namespace App\Policies;

use App\Models\User;
use App\Models\notes;
use Illuminate\Auth\Access\Response;

class NotesPolicy
{
    
    public function modify(User $user, notes $notes): Response
    {
        return $user->id == $notes ->user_id
        ? Response::allow():Response::deny('This is not your posts');
    }
}
