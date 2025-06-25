<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\MenuPosition;
use Illuminate\Support\Facades\Auth;

class MenuPositionController extends Controller
{
    public function store(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        if (Auth::id() !== $restaurant->user_id && Auth::user()?->login !== 'admin') {
            abort(403, 'У вас нет доступа');
        }
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:cocktail,main_course']
        ]);

        MenuPosition::create([
            'restaurant_id' => $restaurantId,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'type' => $validated['type']
        ]);

        return back();
    }
}
