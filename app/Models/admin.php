<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    use HasFactory;
    protected $fillable =[
        'title',
        'creator_username',
        'creator_email',
        'contents',
        'public',
        'note_id'
    ];

    protected $casts = [
        'public' => 'boolean',
    ];
    public function linkToNote()
    {
        return $this->belongsTo(notes::class, 'note_id');
    }
}
