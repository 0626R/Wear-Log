<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserAdminController extends Controller
{
    // ユーザー一覧
    public function index()
    {
        $users = User::orderByDesc('id')->paginate(15);
        return view('admin.dashboard', compact('users'));
    }

    // ユーザー削除
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('status', 'ユーザーを削除しました');
    }
}
