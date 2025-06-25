<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Review;
use App\Models\Favourite;
use App\Models\Restaurant;
use App\Models\MenuPosition;
use Illuminate\Http\Request;
use App\Models\RestaurantImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function show($id)
    {
        $restaurant = Restaurant::with([
            'user',
            'images',
            'menuPositions',
            'reviews.user',
            'favourites',
            'visitors'
        ])->findOrFail($id);

        return Inertia::render('PlaceProfileView', [
            'place' => $restaurant,
            'worktime_start' => substr($restaurant->worktime_start, 0, 5),
            'worktime_end' => substr($restaurant->worktime_end, 0, 5),
            'isFavourited' => auth()->check() && auth()->user()->favourites->contains('restaurant_id', $restaurant->id),
            'user' => auth()->user(),
            'isVisited' => auth()->check() && $restaurant->visitors->contains(auth()->id())
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'INN' => ['required', 'string'],
            'KPP' => ['nullable', 'string'],
            'OGRN' => ['required', 'string'],
            'worktime_start' => ['required', 'date_format:H:i'],
            'worktime_end' => ['required', 'date_format:H:i', 'after:worktime_start'],
            'telegram_url' => ['nullable'],
            'whatsapp_url' => ['nullable'],
            'vk_url' => ['nullable'],
            'description' => ['nullable', 'string']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $validator->validated();
        if (!isset($data['latitude']) || !isset($data['longitude'])) {
            $minLat = 46.0;
            $maxLat = 46.5;
            $minLng = 47.0;
            $maxLng = 48.0;
            $data['latitude'] = rand($minLat * 1e6, $maxLat * 1e6) / 1e6;
            $data['longitude'] = rand($minLng * 1e6, $maxLng * 1e6) / 1e6;
        }
        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'average_price' => 500,
            'rating' => 0,
            'restaurant_mail' => $request->title . '@contact.com',
            ...$data
        ]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = config('app.url') . '/storage' . '/' . $image->store('restaurant_logos', 'public');
                RestaurantImage::create([
                    'restaurant_id' => $restaurant->id,
                    'src' => $path
                ]);
            }
        }

        return redirect()->route('places')
            ->with('flash', ['success' => 'Ресторан успешно создан!']);
    }

    public function edit($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        return Inertia::render('EditPlaceView', [
            'place' => $restaurant,
        ]);
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'INN' => ['required', 'string'],
            'KPP' => ['nullable', 'string'],
            'OGRN' => ['required', 'string'],
            'worktime_start' => ['required', 'date_format:H:i'],
            'worktime_end' => ['required', 'date_format:H:i', 'after:worktime_start'],
            'telegram_url' => ['nullable', 'string'],
            'whatsapp_url' => ['nullable', 'string'],
            'vk_url' => ['nullable', 'string'],
            'description' => ['nullable', 'string']
        ]);
        if ($restaurant->images()->exists()) {
            foreach ($restaurant->images as $image) {
                if (Storage::disk('public')->exists($image->src)) {
                    Storage::disk('public')->delete($image->src);
                }
                $image->delete();
            }
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = config('app.url') . '/storage' . '/' . $image->store('restaurant_logos', 'public');
                RestaurantImage::create([
                    'restaurant_id' => $id,
                    'src' => $path
                ]);
            }
        }
        $validated = $validator->validated();
        $restaurant->update($validated);

        return redirect()->route('place-profile', $restaurant->id)->with('flash', ['success' => 'Заведение отредактировано!']);
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if (auth()->user()->id !== $restaurant->user_id && auth()->user()->login !== 'admin') {
            abort(403, 'Нет доступа');
        }
        $restaurant->delete();

        return redirect()->route('places')->with('flash', ['success' => 'Заведение удалено!']);
    }

    public function visit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if ($restaurant->visitors()->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['already_visited' => 'Вы уже отметили, что посетили это заведение']);
        }
        $restaurant->visitors()->attach(auth()->id());

        return back();
    }

    public function unvisit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->visitors()->detach(auth()->id());

        return back();
    }
}