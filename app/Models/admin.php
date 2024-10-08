<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class admin extends Model
{
    use HasFactory, BelongsToThrough;
    protected $fillable =[
        'notes_id',
        'title',
        'creator_username',
        'creator_email',
        'contents',
        'public',
        'user_id',
        'to_public'
    ];

    protected $casts = [
        'public' => 'boolean',
        'to_public' => 'boolean'
    ];

    public function notes(){
        return $this->belongsTo(notes::class, 'notes_id');
    }

    public function user()
    {
        return $this->belongsToThrough(
            User::class,  // The final model (User)
            notes::class  // The intermediate model (Notes)
        );
    }


}
