<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Favourite;
use App\Models\Restaurant;

class FavouriteController extends Controller
{
    public function add($restaurantId)
    {
        Favourite::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $restaurantId,
        ]);

        return back()->with('success', 'Ресторан добавлен в избранное.');
    }

    public function remove($restaurantId)
    {
        Favourite::where('user_id', auth()->id())
            ->where('restaurant_id', $restaurantId)
            ->delete();

        return back()->with('success', 'Ресторан удален из избранного.');
    }
}