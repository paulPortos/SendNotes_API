<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forgot_pass extends Model
{
    use HasFactory;

    protected $table = 'forgot_password'; 
    protected $guarded = [];

    protected $fillable =[
        'email',
        'token'
    ];

}
