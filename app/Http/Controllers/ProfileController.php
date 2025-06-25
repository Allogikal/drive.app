<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'sex' => ['required', 'in:male,female'],
            'src' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user->fill($request->only(['name', 'surname', 'date', 'sex']));
        if ($request->hasFile('src')) {
            $path = config('app.url') . '/storage' . '/' . $request->file('src')->store('avatars', 'public');
            $user->src = $path;
        }
        $user->save();

        return back()->with('success', 'Профиль успешно обновлен.');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Аккаунт был удален.');
    }
}
