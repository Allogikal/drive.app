<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\UserImage;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function applications()
    {
        $applications = Restaurant::with([
            'user',
            'images'
        ])->where('status', 'inactive')->get();

        return Inertia::render('Admin/AdminPage1View', [
            'applications' => $applications
        ]);
    }

    public function approveApplication($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['status' => 'active']);

        return back()->with('success', 'Заявка принята!');
    }

    public function rejectApplication($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return back()->with('success', 'Заявка отклонена!');
    }

    public function users()
    {
        $users = User::all();
        return Inertia::render('Admin/AdminPage2View', [
            'users' => $users
        ]);
    }

    public function blockUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => 'inactive',
            'reason' => $request->input('reason')
        ]);

        return back();
    }

    public function unblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => 'active',
            'reason' => null
        ]);

        return back();
    }

    public function places()
    {
        $restaurants = Restaurant::with([
            'user',
            'images',
            'reviews'
        ])->get();

        return Inertia::render('Admin/AdminPage3View', [
            'restaurants' => $restaurants,
        ]);
    }

    public function gallery()
    {
        $userImages = UserImage::where('status', 'pending')
        ->with(['user.restaurants'])
        ->orderBy('created_at', 'desc')
        ->get();

    return Inertia::render('Admin/AdminPage5View', [
        'userImages' => $userImages,
    ]);
    }

    public function acceptUserImage($id)
    {
        $image = UserImage::findOrFail($id);
        $image->update(['status' => 'active']);

        return back();
    }

    public function rejectUserImage($id)
    {
        $image = UserImage::findOrFail($id);
        $image->delete();

        return back();
    }
}
