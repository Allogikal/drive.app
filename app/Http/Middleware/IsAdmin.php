<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        if ($user->login === 'admin') {
            if (!$request->is('admin*')) {
                return route('admin-applications');
            }
        } else {
            abort(403, 'Доступ запрещён');
        }

        return $next($request);
    }
}