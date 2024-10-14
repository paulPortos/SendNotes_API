<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments'; 

    protected $fillable =[
        'username',
        'comment',
        'notes_id',
         'user_id'
    ];
    public function notes(){
        return $this->belongsTo(notes::class, 'notes_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
