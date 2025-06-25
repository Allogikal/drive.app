<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Restaurant;
use App\Models\VisitedRestaurant;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name', 'surname', 'login', 'date', 'sex',
        'status', 'reason', 'src', 'password',
    ];

    protected $hidden = [
        'remember_token',
        'sanctum_tokens'
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(UserImage::class);
    }

    public function visitedRestaurants()
    {
        return $this->hasMany(VisitedRestaurant::class);
    }

    public function restaurantsVisited()
    {
        return $this->belongsToMany(Restaurant::class, 'visited_restaurants')
                    ->withTimestamps();
    }
}