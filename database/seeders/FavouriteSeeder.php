<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favourite;
use App\Models\User;
use App\Models\Restaurant;

class FavouriteSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $restaurants = Restaurant::all();

        foreach ($users as $user) {
            $randomRestaurants = $restaurants->random(2);

            foreach ($randomRestaurants as $restaurant) {
                Favourite::create([
                    'user_id' => $user->id,
                    'restaurant_id' => $restaurant->id,
                ]);
            }
        }
    }
}