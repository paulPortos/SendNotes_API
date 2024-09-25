<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    use HasFactory;
    protected $fillable =[
        'title',
        'creator',
        'contents',
        'public',
    ];

    protected $casts = [
        'public' => 'boolean',
    ];
}
