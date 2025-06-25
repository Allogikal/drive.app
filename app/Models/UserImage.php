<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    protected $fillable = ['user_id', 'description', 'src', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
