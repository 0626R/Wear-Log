<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;


class UnifiedLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // 既存のログインBladeそのままでOK
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        // 管理者メールなら admin でログイン
    if (\App\Models\Admin::where('email', $cred['email'])->exists()) {
        if (Auth::guard('admin')->attempt($cred, $request->boolean('remember'))) {
            // 片方だけにする：web で入ってたら落とす
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }
            $request->session()->regenerate();
            $request->session()->forget('url.intended');
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['email' => '管理者ログインに失敗しました。'])->onlyInput('email');
    }

    // 一般/プレミアムは web でログイン
    if (Auth::guard('web')->attempt($cred, $request->boolean('remember'))) {
        // 片方だけにする：admin で入ってたら落とす
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        $request->session()->regenerate();
        return redirect()->intended(route('items.home'));
    }

    return back()->withErrors(['email' => 'ログインに失敗しました。'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // 念のため両方落とす
    Auth::guard('admin')->logout();
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
    }
}
