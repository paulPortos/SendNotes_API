<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //defines the relation to the note model from the user model
    public function linkToNotes(){
        return $this->hasMany(notes::class);
    }

    public function linkToFlashcards()
    {
        return $this->hasMany(Flashcards::class);
    }

    public function admins()
    {
        return $this->hasManyThrough(
            Admin::class,
            Notes::class,
            'user_id',    // Foreign key on the notes table (notes.user_id)
            'notes_id',   // Foreign key on the admin table (admin.notes_id)
            'id',         // Local key on the user table (users.id)
            'id'          // Local key on the notes table (notes.id)
        );
    }


    public function comment()
    {
    return $this->hasManyThrough(Comments::class, notes::class, 'user_id', 'notes_id');
    }

    public function notification()
    {
    return $this->hasMany(Notifications::class);
    }

    public function linktocomments()
    {
        return $this->hasMany(Comments::class);
    }

    public function linktoReactions()
    {
        return $this->hasMany(Reactions::class);
    }

    public function linkToSendNotes()
    {
        return $this->hasMany(Reactions::class);
    }
}
