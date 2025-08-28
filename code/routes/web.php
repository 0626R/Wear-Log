<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLogin;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CalendarController;

Route::view('/statistics', 'stubs.statistics')->name('statistics');
Route::view('/mypage', 'stubs.mypage')->name('mypage');

Route::get('/items', [\App\Http\Controllers\ItemController::class,'home'])->name('items.home');
Route::get('/items/create', [\App\Http\Controllers\ItemController::class,'create'])->name('items.create');

// カテゴリ選択画面を表示
Route::get('/items/select-category', [ItemController::class, 'selectCategory'])
    ->name('items.selectCategory');

// カテゴリ選択を保存（POST）
Route::post('/items/select-category', [ItemController::class, 'storeCategorySelection'])
    ->name('items.storeCategorySelection');

    Route::get('/items/select-color', [ItemController::class, 'selectColor'])->name('items.selectColor');
    Route::post('/items/store-color-selection', [ItemController::class, 'storeColorSelection'])->name('items.storeColorSelection');

    // いま入力している内容をセッションに保存するAPI
Route::post('/items/save-draft', [ItemController::class, 'saveDraft'])->name('items.saveDraft');

Route::get('/items/clear-selected-colors', function () {
    session()->forget('selected_colors');
    return redirect()->route('items.selectColor');
})->name('items.clearSelectedColors');



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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/calendar',  [CalendarController::class, 'index'])->name('calendar');
    Route::post('/calendar/upload', [CalendarController::class, 'store'])->name('calendar.upload');
});

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
    Route::get('/items/filter', [ItemController::class, 'filter'])->name('items.filter');

});

// ⑤ 管理者（admin）
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [UserAdminController::class, 'index'])->name('dashboard');
    Route::delete('/users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/items', [ItemController::class,'home'])->name('items.home');
    Route::get('/items/create', [ItemController::class,'create'])->name('items.create');
    Route::post('/items', [ItemController::class,'store'])->name('items.store');
});



Route::get('/others', [UserController::class, 'others'])->name('others');

// ログアウト用
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
//     Route::get('/others',  [UsersController::class, 'others'])->name('others');
//     Route::post('/logout', [UsersController::class, 'logout'])->name('logout');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [UsersController::class, 'others'])->name('profile.edit'); // ←追加
//     Route::post('/logout', [UsersController::class, 'logout'])->name('logout');
// });

// 認証必須
// Route::middleware('auth')->group(function () {
//     Route::get('/admin/dashboard', [UserController::class, 'index'])
//         ->name('admin.dashboard')
//         ->middleware('admin'); // 管理者用ミドルウェア

//     Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
//         ->name('admin.users.destroy')
//         ->middleware('admin');
// });

// ユーザー（一般・プレミアム）
// Auth::routes();

// 管理者
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Route::middleware('auth:admin')->group(function () {
//     Route::get('/admin/dashboard', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.dashboard');
// });

// ログイン画面（共通）
Route::get('/login', [UnifiedLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UnifiedLoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [UnifiedLoginController::class, 'logout'])->name('logout');



// 管理者専用（adminガード必須）
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminUserController::class, 'index'])->name('admin.dashboard');
});
 
// 一般ユーザー用（webガード）
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home'); // 任意のホーム
    })->name('home');
});

Route::middleware('auth.any')->group(function () {
    Route::get('/others', [ProfileController::class, 'show'])->name('others');           // ←「その他」ページ
    Route::get('/admin/dashboard', [AdminUserController::class, 'index'])
        ->middleware('auth:admin')->name('admin.dashboard');                            // 管理者ダッシュボード
});
Route::get('/others', [ProfileController::class, 'show'])
    ->middleware('auth:admin,web')   // ← ここがポイント
    ->name('others');

    // 管理者（admin）
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [UserAdminController::class, 'index'])->name('dashboard');
    Route::delete('/users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');
});

Route::get('/items/{item}',        [ItemController::class, 'show'])->name('items.show');

Route::get('/items/{item}',        [ItemController::class, 'show'])->name('items.show');
Route::get('/items/{item}/edit',   [ItemController::class, 'edit'])->name('items.edit');
Route::put('/items/{item}',        [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{item}',     [ItemController::class, 'destroy'])->name('items.destroy');
Route::delete('/items/{item}',     [ItemController::class, 'destroy'])->name('items.destroy');


