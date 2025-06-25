<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        Restaurant::create([
            'title' => 'Purple Bar',
            'description' => 'Purple street bar — это бар в Астрахани, который предлагает своим посетителям окунуться в атмосферу вкусной еды и прохладных напитков. В заведении есть обширная пивная карта и всевозможные снеки, которые дополняют пенный напиток. Здесь можно встретиться с друзьями под негромкую музыку и хорошо провести время.',
            'worktime_start' => '19:00',
            'worktime_end' => '02:00',
            'phone' => '+79170912210',
            'address' => 'ул. Ахматовская, д. 3',
            'average_price' => '1500',
            'restaurant_mail' => 'purple.bar@astrakhan.ru',
            'INN' => '123456789012',
            'OGRN' => '100123456789012',
            'status' => 'active',
            'rating' => 4.7,
            'user_id' => 1,
            'telegram_url' => 'https://t.me/purple_bar_astrakhan', 
            'whatsapp_url' => 'https://wa.me/79170912210', 
            'vk_url' => 'https://vk.com/purple.bar.astrakhan', 
        ]);

        Restaurant::create([
            'title' => 'Basil Smash',
            'description' => 'Basil Smash — место, где вы можете окунуться в атмосферу вкусной еды и прохладных напитков. В меню представлены авторские коктейли, разнообразные закуски и основные блюда европейской и азиатской кухни. Уютная атмосфера и качественная музыка делают его идеальным выбором для романтической встречи или просто отличного вечера.',
            'worktime_start' => '18:00',
            'worktime_end' => '23:00',
            'phone' => '+79171234567',
            'address' => 'ул. Смоляная, д. 15',
            'average_price' => '1000',
            'restaurant_mail' => 'basil.smash@astrakhan.ru',
            'INN' => '234567890123',
            'OGRN' => '100234567890123',
            'status' => 'active',
            'rating' => 4.6,
            'user_id' => 2,
            'telegram_url' => 'https://t.me/basilsmastra', 
            'whatsapp_url' => 'https://wa.me/79171234567', 
            'vk_url' => 'https://vk.com/basilsmastra', 
        ]);

        Restaurant::create([
            'title' => 'The Red Lion',
            'description' => 'The Red Lion — бар английского стиля, расположенный в исторической части Астрахани. Здесь подают крафтовое пиво, имбирный эль, виски и другие алкогольные напитки. Есть отдельная зона для кальяна и небольшая барная стойка.',
            'worktime_start' => '19:00',
            'worktime_end' => '01:00',
            'phone' => '+79172345678',
            'address' => 'ул. Гагарина, д. 42',
            'average_price' => '800',
            'restaurant_mail' => 'redlion@example.com',
            'INN' => '345678901234',
            'OGRN' => '100345678901234',
            'status' => 'active',
            'rating' => 4.5,
            'user_id' => 3,
            'telegram_url' => 'https://t.me/redlionastrakhan', 
            'vk_url' => 'https://vk.com/the_red_lion', 
        ]);

        Restaurant::create([
            'title' => 'Barista Coffee & Co.',
            'description' => 'Уютный кофейный бар с широким выбором кофейных напитков, капучино, латте, эспрессо и много другого. Также есть простые закуски и выпечка. Место для любителей кофе и тихого вечера.',
            'worktime_start' => '10:00',
            'worktime_end' => '22:00',
            'phone' => '+79173456789',
            'address' => 'ул. Базарная, д. 22',
            'average_price' => '1000',
            'restaurant_mail' => 'barista.coffee@astrakhan.ru',
            'INN' => '456789012345',
            'OGRN' => '100456789012345',
            'status' => 'active',
            'rating' => 4.4,
            'user_id' => 1,
            'vk_url' => 'https://vk.com/baristaastrakhan', 
        ]);

        Restaurant::create([
            'title' => 'La Piazza',
            'description' => 'Итальянский ресторан в центре Астрахани. Здесь подают настоящие итальянские блюда: пиццу, спагетти, ризотто и многое другое. Работает с 11:00 до 23:00, есть отдельная детская зона и Wi-Fi. Идеальное место для семейных обедов и романтических ужинов.',
            'worktime_start' => '11:00',
            'worktime_end' => '23:00',
            'phone' => '+79174567890',
            'address' => 'ул. Ленина, д. 18',
            'average_price' => '2500',
            'restaurant_mail' => 'lapizza@example.com',
            'INN' => '567890123456',
            'OGRN' => '100567890123456',
            'status' => 'active',
            'rating' => 4.6,
            'user_id' => 2,
            'telegram_url' => 'https://t.me/lapizzaastrakhan', 
            'vk_url' => 'https://vk.com/lapizzarussia', 
        ]);

        Restaurant::create([
            'title' => 'Ресторан "У Ларисы"',
            'description' => 'Традиционная узбекская кухня. Здесь подают плов, шурпу, шашлык и другие национальные блюда. Просторное помещение с домашней атмосферой и уютным интерьером. Подходит как для обеда, так и для вечернего отдыха.',
            'worktime_start' => '10:00',
            'worktime_end' => '22:00',
            'phone' => '+79175678901',
            'address' => 'ул. Ленина, д. 10',
            'average_price' => '800',
            'restaurant_mail' => 'larisa.restaurant@astrakhan.ru',
            'INN' => '678901234567',
            'OGRN' => '100678901234567',
            'status' => 'active',
            'rating' => 4.3,
            'user_id' => 3,
        ]);

        Restaurant::create([
            'title' => 'Пиццерия "Домино"',
            'description' => 'Итальянская пицца на заказ. Широкий выбор соусов, начинок и размеров. Все пиццы приготовлены из натуральных продуктов и свежего теста. Отличное место для быстрого перекуса или полноценного ужина.',
            'worktime_start' => '11:00',
            'worktime_end' => '23:00',
            'phone' => '+79176789012',
            'address' => 'пр. Победы, д. 45',
            'average_price' => '1200',
            'restaurant_mail' => 'domino.pizza@astrakhan.ru',
            'INN' => '789012345678',
            'OGRN' => '100789012345678',
            'status' => 'inactive',
            'rating' => 3.2,
            'user_id' => 1,
        ]);
    }
}