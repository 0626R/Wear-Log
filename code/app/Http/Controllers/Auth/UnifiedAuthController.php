<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnifiedAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.unified-login'); // ③ の Blade
    }

    public function login(Request $req)
    {
        $cred = $req->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);
        $remember = $req->boolean('remember');

        // 1) まず admin ガードで試す
        if (Auth::guard('admin')->attempt($cred, $remember)) {
            $req->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // 2) 次に web（一般ユーザー）で試す
        if (Auth::guard('web')->attempt($cred, $remember)) {
            $req->session()->regenerate();
            return redirect()->route('items.home'); // 既存ホーム
        }

        return back()->withErrors(['email' => 'メールアドレスまたはパスワードが違います。'])
                     ->onlyInput('email');
    }

    public function logout(Request $req)
    {
        // どちらのガードでもログアウトできるように
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegister()
    {
        return view('auth.register'); 
    }

    public function register(Request $req)
    {
        $data = $req->validate([
            'email'                 => ['required','email','unique:users,email'],
            'password'              => ['required','min:8','confirmed'],
            'last_name'             => ['nullable','max:50'],
            'first_name'            => ['nullable','max:50'],
        ]);

        $user = User::create([
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'last_name'  => $data['last_name'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'is_premium' => false, // 初期は一般
        ]);

        // 登録後はログイン画面へ
        return redirect()->route('login')->with('status','登録が完了しました。ログインしてください。');
    }
}
