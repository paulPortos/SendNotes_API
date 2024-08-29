<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcards extends Model
{
    use HasFactory;

    protected $fillable =[
        'username',
        'title',
        'contents',
        'public'
    ];
    
    public function linkToUser(){
        return $this->belongsTo(User::class);
    }
}
