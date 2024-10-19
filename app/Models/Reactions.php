<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reactions extends Model
{
    use HasFactory;

    protected $fillable =[
        'has_liked',
        'has_disliked',
        'notes_id',
        'user_id',
    ];

    protected $casts = [
        'has_liked' => 'boolean',
        'has_disliked' => 'boolean',
    ];

    public function notes(){
        return $this->belongsTo(notes::class, 'notes_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
