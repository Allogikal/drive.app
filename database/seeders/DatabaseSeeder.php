<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\MenuPosition;
use App\Models\Review;
use App\Models\Favourite;
use App\Models\RestaurantImage;
use App\Models\UserImage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем пользователей
        $this->call(UserSeeder::class);

        // Создаем рестораны
        $this->call(RestaurantSeeder::class);

        // Создаем меню для ресторанов
        $this->call(MenuPositionSeeder::class);

        // Создаем отзывы
        $this->call(ReviewSeeder::class);

        // Создаем избранное
        $this->call(FavouriteSeeder::class);

        // Создаем изображения ресторанов
        $this->call(RestaurantImageSeeder::class);

        // Создаем изображения пользователей
        $this->call(UserImageSeeder::class);
    }
}