<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id', // This is the only foreign key we need in this table
        'contents',
        'title'
    ];

    // Relationship to Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // Access the Note through Admin
    public function note()
    {
        return $this->hasOneThrough(
            Notes::class, // Final Model (Notes)
            Admin::class, // Intermediate Model (Admin)
            'id', // Foreign key on the admin table (admin.id)
            'id', // Foreign key on the notes table (notes.id)
            'admin_id', // Local key on publicnotes table (publicnotes.admin_id)
            'notes_id'  // Foreign key on the admin table (admin.notes_id)
        );
    }

    // Access the User through the Note (via Admin)
    public function user()
    {
        return $this->hasOneThrough(
            User::class,  // Final Model (User)
            Notes::class, // Intermediate Model (Notes)
            'id',         // Foreign key on the notes table (notes.id)
            'id',         // Foreign key on the user table (users.id)
            'admin_id',   // Local key on publicnotes table (publicnotes.admin_id)
            'user_id'     // Foreign key on the notes table (notes.user_id)
        );
    }
}

