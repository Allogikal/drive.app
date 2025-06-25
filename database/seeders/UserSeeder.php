<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class UserSeeder extends Seeder
{
    public function run()
    {
        $response = Http::get('https://randomuser.me/api/'); 
        $data = $response->json();

        User::create([
            'name' => $data['results'][0]['name']['first'],
            'surname' => $data['results'][0]['name']['last'],
            'login' => strtolower($data['results'][0]['login']['username']),
            'password' => bcrypt('password'),
            'sex' => $data['results'][0]['gender'] === 'male' ? 'male' : 'female',
            'status' => 'active',
            'src' => config('app.url') . '/storage/avatars/8ktCc2UASj614emFwjzYNoW4KacjH3Que22fnz8E.webp',
            'date' => now()->subYears(rand(18, 60))->toDateString(),
        ]);

        $response = Http::get('https://randomuser.me/api/'); 
        $data = $response->json();

        User::create([
            'name' => $data['results'][0]['name']['first'],
            'surname' => $data['results'][0]['name']['last'],
            'login' => strtolower($data['results'][0]['login']['username']),
            'password' => bcrypt('password'),
            'sex' => $data['results'][0]['gender'] === 'male' ? 'male' : 'female',
            'status' => 'inactive',
            'reason' => 'Нарушение правил',
            'src' => config('app.url') . '/storage/avatars/8ktCc2UASj614emFwjzYNoW4KacjH3Que22fnz8E.webp',
            'date' => now()->subYears(rand(18, 60))->toDateString(),
        ]);

        $response = Http::get('https://randomuser.me/api/'); 
        $data = $response->json();

        User::create([
            'name' => $data['results'][0]['name']['first'],
            'surname' => $data['results'][0]['name']['last'],
            'login' => 'admin',
            'password' => bcrypt('admin'),
            'sex' => $data['results'][0]['gender'] === 'male' ? 'male' : 'female',
            'status' => 'active',
            'src' => config('app.url') . '/storage/avatars/8ktCc2UASj614emFwjzYNoW4KacjH3Que22fnz8E.webp',
            'date' => now()->subYears(rand(18, 60))->toDateString(),
        ]);
    }
}