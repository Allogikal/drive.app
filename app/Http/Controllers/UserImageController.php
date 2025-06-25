<?php

namespace App\Http\Controllers;

use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['required', 'string']
        ]);
        $path = config('app.url') . '/storage' . '/' . $request->image->store('restaurant_logos', 'public');
        UserImage::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'src' => $path
        ]);

        return back();
    }
}
