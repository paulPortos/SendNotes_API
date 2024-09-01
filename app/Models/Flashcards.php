<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcards extends Model
{
    use HasFactory;

    protected $fillable =[
        'title',
        'cards',
        'public',
        'to_public',
    ];

    protected $casts = [
        'cards' => 'array',
    ];

    public function linkToUser(){
        return $this->belongsTo(User::class);
    }
}
