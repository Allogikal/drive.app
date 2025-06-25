<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserImage;
use App\Models\User;

class UserImageSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                UserImage::create([
                    'user_id' => $user->id,
                    'src' => "https://placehold.co/300x300?text=Фото+{$user->id}+({$i})",
                    'description' => $this->getDescription($i),
                    'status' => 'active',
                ]);

                UserImage::create([
                    'user_id' => $user->id,
                    'src' => "https://placehold.co/300x300?text=Фото+{$user->id}+({$i})",
                    'description' => $this->getDescription($i),
                    'status' => 'pending',
                ]);
            }
        }
    }

    private function getDescription($imageNumber)
    {
        $descriptions = [
            'Главное фото пользователя',
            'Снимок в баре',
            'Фотография на мероприятии',
            'Пользовательский портрет',
            'Заведение, которое я посетил',
            'Мое любимое место в городе',
            'Фото профиля',
            'Снимок с друзьми',
            'Красивый вид на Астрахань',
            'Свободная тема'
        ];
        return $descriptions[array_rand($descriptions)];
    }
}