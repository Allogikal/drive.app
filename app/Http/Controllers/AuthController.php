<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $ip = $request->ip();
        $key = 'captcha_' . $ip;
        if (!Cache::has($key) || Cache::get($key) !== $request->input('captcha')) {
            return response()->json([
                'success' => false,
                'message' => 'CAPTCHA не совпадает',
                'errors' => ['captcha' => 'Неверный код CAPTCHA'],
            ], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'unique:users,login'],
            'date' => ['required', 'date'],
            'sex' => ['required', 'in:female,male'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка ввода данных',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'login' => $request->login,
            'date' => $request->date,
            'sex' => $request->sex,
            'password' => Hash::make($request->password),
            'src' => config('app.url') . '/storage/avatars/8ktCc2UASj614emFwjzYNoW4KacjH3Que22fnz8E.webp',
        ]);
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно зарегистрировались!',
            'redirect' => route('home'),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => ['Неверный логин или пароль.']
            ]);
        }
        $user = Auth::user();
        $user->touch();
        $request->session()->regenerate();

        if ($user->login === 'admin') {
            return response()->json([
                'success' => true,
                'message' => 'Вы успешно вошли панель управления.',
                'isAdmin' => true,
                'redirect' => route('admin-applications'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно вошли в систему.',
            'redirect' => route('home'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно вышли из системы.',
            'redirect' => route('login'),
        ]);
    }

    public function generateCaptcha(Request $request)
    {
        try {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = substr(str_shuffle($characters), 0, 6);
            $key = 'captcha_' . $request->ip();
            Cache::put($key, $code, now()->addMinutes(5));
            $image = imagecreate(120, 40);
            $bgColor = imagecolorallocate($image, 255, 255, 255);
            $textColor = imagecolorallocate($image, 0, 0, 0);
            imagestring($image, 5, 10, 10, $code, $textColor);
            ob_start();
            imagepng($image);
            $imageData = base64_encode(ob_get_clean());
            imagedestroy($image);

            return response()->json([
                'image' => 'data:image/png;base64,' . $imageData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Не удалось сгенерировать каптчу.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}