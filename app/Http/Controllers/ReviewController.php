<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Restaurant;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return Inertia::render('ReviewFormView', ['restaurant' => $restaurant]);
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'rate' => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'restaurant_id' => $id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'rate' => $validated['rate'],
        ]);

        $restaurant = Restaurant::findOrFail($id);
        $restaurant->updateRating();

        return redirect()->route('place-profile', ['id' => $id])->with('success', 'Отзыв добавлен!');
    }

    public function index()
    {
        $reviews = Review::with(['user', 'restaurant'])
            ->where('status', 'active')
            ->get();

        return Inertia::render('Admin/AdminPage4View', [
            'reviews' => $reviews,
        ]);
    }

    public function ban(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'status' => 'inactive',
            'ban_reason' => $request->input('reason'),
        ]);

        return back();
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Отзыв успешно удален!');
    }
}