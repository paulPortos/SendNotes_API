<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reactions extends Model
{
    use HasFactory;

    protected $fillable =[
        'likes',
        'dislikes',
        'notes_id',
        'user_id',
    ];

    public function notes(){
        return $this->belongsTo(notes::class, 'notes_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
