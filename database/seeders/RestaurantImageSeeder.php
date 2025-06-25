<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantImage;
use App\Models\Restaurant;

class RestaurantImageSeeder extends Seeder
{
    public function run()
    {
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            RestaurantImage::create([
                'restaurant_id' => $restaurant->id,
                'src' => 'https://images.unsplash.com/photo-1735048033337-7356500ac282?q=80&w=988&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ]);

            RestaurantImage::create([
                'restaurant_id' => $restaurant->id,
                'src' => 'https://images.unsplash.com/photo-1621802743320-83199eb8075e?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ]);

            RestaurantImage::create([
                'restaurant_id' => $restaurant->id,
                'src' => 'https://images.unsplash.com/photo-1676887736195-edd299dee288?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ]);

            RestaurantImage::create([
                'restaurant_id' => $restaurant->id,
                'src' => 'https://images.unsplash.com/photo-1615472847561-b745cf6a2fec?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ]);
        }
    }
}