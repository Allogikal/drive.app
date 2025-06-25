<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\MenuPosition;

class MenuPositionSeeder extends Seeder
{
    public function run()
    {
        MenuPosition::create([
            'restaurant_id' => 1,
            'title' => 'Пурпурный клуб',
            'description' => 'Классический коктейль на основе джина с черничным лаймом и легким вкусом мятного тоника.',
            'price' => 400.00,
            'type' => 'cocktail',
        ]);

        MenuPosition::create([
            'restaurant_id' => 2,
            'title' => 'Страстная ягода',
            'description' => 'Коктейль с водкой, смородровым сиропом, свежевыжатым апельсиновым соком и гренадином.',
            'price' => 350.00,
            'type' => 'cocktail',
        ]);

        MenuPosition::create([
            'restaurant_id' => 1,
            'title' => 'Тропическая ночь',
            'description' => 'Смесь рома, папайи, ананаса и мятного ликера. Отлично подходит для вечернего отдыха.',
            'price' => 420.00,
            'type' => 'cocktail',
        ]);

        MenuPosition::create([
            'restaurant_id' => 3,
            'title' => 'Плов по-узбекски',
            'description' => 'Традиционный узбекский плов с рисом, говядиной, луком, помидорами, зеленью и специями. Подается с маринованными овощами.',
            'price' => 300.00,
            'type' => 'main_course',
        ]);

        MenuPosition::create([
            'restaurant_id' => 4,
            'title' => 'Шурпа',
            'description' => 'Овощной суп с бараниной, фасолью, томатами, перцем и специями. Тёплый, питательный и очень вкусный.',
            'price' => 250.00,
            'type' => 'main_course',
        ]);

        MenuPosition::create([
            'restaurant_id' => 1,
            'title' => 'Бефстроганов',
            'description' => 'Говядина, тушеная в сливочно-грибном соусе, подается с рисом и зеленым салатом.',
            'price' => 380.00,
            'type' => 'main_course',
        ]);

        MenuPosition::create([
            'restaurant_id' => 2,
            'title' => 'Паста карбонара',
            'description' => 'Итальянская паста с яйцами, сыром пармезан, чесноком и колбасой панчеттоне.',
            'price' => 450.00,
            'type' => 'main_course',
        ]);

        MenuPosition::create([
            'restaurant_id' => 5,
            'title' => 'Фалафель',
            'description' => 'Рецепт домашней фалафели из бобовых, пряностей и овощей. Подается с хумусом и овощными закусками.',
            'price' => 200.00,
            'type' => 'main_course',
        ]);

        MenuPosition::create([
            'restaurant_id' => 4,
            'title' => 'Чизкейк "Астраханский"',
            'description' => 'Мягкий чизкейк с карамельным вкусом и свежими ягодами. Идеально подходит к кофе или чайному столу.',
            'price' => 180.00,
            'type' => 'main_course',
        ]);

        MenuPosition::create([
            'restaurant_id' => 3,
            'title' => 'Эспрессо',
            'description' => 'Сильный и ароматный эспрессо из лучшей арабики. Сделайте ваш день более энергичным.',
            'price' => 120.00,
            'type' => 'main_course',
        ]);
    }
}