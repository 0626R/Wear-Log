<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLogin;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Admin\UserAdminController;



// // Route::get('/', function () {
// //     return view('welcome');
// // });

// // ルート: 未ログインならログイン画面、ログイン済みならホーム
// Route::get('/', function () {
//     return auth()->check()
//         ? redirect()->route('items.home')
//         : redirect()->route('login');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// // 一般ユーザーのホーム
// Route::get('/home', fn() => redirect()->route('items.home'))->name('home');

// // 管理者ログイン
// Route::get ('/admin/login',  [AdminLogin::class, 'create'])->name('admin.login');
// Route::post('/admin/login',  [AdminLogin::class, 'store']);
// Route::post('/admin/logout', [AdminLogin::class, 'destroy'])->name('admin.logout');

// // 管理者専用エリア
// Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'destroy'])->name('users.destroy');
// });

// Route::prefix('admin')->group(function () {
//     Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('login', [AdminLoginController::class, 'login']);
//     Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
// });

// ① トップ = 統合ログイン（表示）
Route::get('/', [UnifiedAuthController::class, 'showLogin'])->name('login');

// /login 直打ちでも同じ画面を出したい場合
Route::get('/login', fn () => redirect()->route('login'));

// ② 認証（送信先）
Route::post('/login', [UnifiedAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout');

// ③ 会員登録
Route::get('/register', [UnifiedAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [UnifiedAuthController::class, 'register'])->name('register.post');

// ④ 一般ユーザー（web）
Route::middleware('auth')->group(function () {
    Route::get('/home', fn() => redirect()->route('items.home'))->name('home');
    Route::get('/items', [ItemController::class, 'home'])->name('items.home');
});

// ⑤ 管理者（admin）
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [UserAdminController::class, 'index'])->name('dashboard');
    Route::delete('/users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');
});
