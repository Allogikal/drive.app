<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Restaurant;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $restaurants = Restaurant::all();
        $users = User::all();
        
        $reviewTexts = [
            'Отличное место для отдыха! Всем рекомендую.',
            'Очень вкусная еда, атмосфера уютная и дружелюбная.',
            'Все очень понравилось, особенно обслуживание и коктейли.',
            'Ресторан с высоким качеством блюд и приятной музыкой.',
            'Мне не очень понравилось, но есть что-то интересное в меню.',
            'Классный бар с живой музыкой и хорошим выбором напитков.',
            'Плохое обслуживание, но еда — достойная.',
            'Интерьер потрясающий, но цены немножко завышены.',
            'Замечательное место для встречи с друзьями вечером.',
            'Не лучший выбор, если вы хотите быстро поесть.',
            'Средний чек соответствует качеству еды и обслуживания.',
            'Вкусно, удобное расположение, обязательно вернусь.',
            'Для романтической встречи подходит идеально.',
            'Приятный перекус на скорую руку.',
            'Очень оригинальные коктейли и дружелюбный персонал.',
            'Меню разнообразное, всё свежее и приготовлено вкусно.',
            'Если вы любите европейскую кухню — это ваше место.',
            'Большая очередь, но стоит того.',
            'Одна из лучших пивных Астрахани.',
            'Надо бы больше таких мест в городе.'
        ];

        foreach ($restaurants as $restaurant) {
            for ($i = 0; $i < rand(3, 6); $i++) {
                $user = $users->random();
                $rate = rand(1, 5);
                $content = $reviewTexts[array_rand($reviewTexts)];
                Review::create([
                    'restaurant_id' => $restaurant->id,
                    'user_id' => $user->id,
                    'content' => $content,
                    'rate' => $rate,
                    'status' => 'active',
                ]);
            }
        }
    }
}