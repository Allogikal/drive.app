<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPosition extends Model
{
    protected $fillable = ['restaurant_id', 'title', 'description', 'price', 'type'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
