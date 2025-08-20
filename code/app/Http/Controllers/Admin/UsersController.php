<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UsersController extends Controller
{
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('status','削除しました');
    }
}
