<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


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
}
