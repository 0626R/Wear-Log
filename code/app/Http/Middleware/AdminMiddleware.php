<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 管理者かどうかチェック（メールアドレスで判定）
        if (Auth::check() && Auth::user()->email === 'admin@example.com') {
            return $next($request);
        }

        // 管理者でなければホームにリダイレクト
        return redirect('/');
    }
}
