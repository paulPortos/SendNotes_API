<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notes extends Model
{
    use HasFactory;

    protected $fillable =[
        'title',
        'contents'
    ];
    //defines a relationship between the notes model and the User model.
    public function linkToUser(){
        return $this->belongsTo(User::class);
    }
}
