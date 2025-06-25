<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'title', 'description', 'worktime_start', 'worktime_end',
        'phone', 'restaurant_site_url', 'address', 'average_price',
        'owner_name', 'owner_surname', 'owner_patronymic', 'restaurant_mail',
        'INN', 'KPP', 'OGRN', 'telegram_url', 'whatsapp_url', 'vk_url',
        'status', 'rating', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menuPositions()
    {
        return $this->hasMany(MenuPosition::class);
    }

    public function images()
    {
        return $this->hasMany(RestaurantImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function visitors()
    {
        return $this->belongsToMany(User::class, 'visited_restaurants')
                    ->withTimestamps();
    }

    protected static function booted()
    {
        static::creating(function ($restaurant) {
            if (empty($restaurant->latitude) || empty($restaurant->longitude)) {
                $restaurant->latitude = self::generateRandomLatitude();
                $restaurant->longitude = self::generateRandomLongitude();
            }
        });
    }

    private static function generateRandomLatitude()
    {
        return number_format(rand(36000000, 76500000) / 1000000, 6);
    }

    private static function generateRandomLongitude()
    {
        return number_format(rand(38000000, 78500000) / 1000000, 6);
    }

    // FOR RATE SYSTEM . . .

    public function updateRating()
    {
        $average = $this->reviews()->avg('rate');
        $this->update(['rating' => $average]);
    }
}