<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicNotes extends Model
{
    use HasFactory;
    protected $fillable =[
        'title',
        'creator_username',
        'creator_email',
        'contents',
        'public',
    ];

    protected $casts = [
        'public' => 'boolean',
    ];
}
