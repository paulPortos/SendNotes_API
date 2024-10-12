<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable =[
        'notes_id',
        'notes_title',
        'notification_type',
        'email',
        'message',
        'user_id',
    ];

    public function notes(){
        return $this->belongsTo(notes::class, 'notes_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
