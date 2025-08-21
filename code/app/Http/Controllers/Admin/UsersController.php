<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success','ユーザーを削除しました');
    }

    public function others()
    {
        $user = Auth::user(); // ログイン中のユーザー情報を取得
        return view('users.others', compact('user'));
    }
    // 会員情報
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // ログアウト
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'ログアウトしました');
    }
}
