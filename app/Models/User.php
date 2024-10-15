<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
    return $this->hasManyThrough(admin::class, notes::class, 'user_id', 'notes_id');
    }

    public function comment()
    {
    return $this->hasManyThrough(Comments::class, notes::class, 'user_id', 'notes_id');
    }

    public function notifacation()
    {
    return $this->hasManyThrough(Notifications::class, notes::class, 'user_id', 'notes_id');
    }

    public function linktocomments()
    {
        return $this->hasMany(Comments::class);
    }
}
