<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\MypageController;


// "/" にアクセスしたら、ItemController の home メソッドを呼ぶ
Route::get('/', [ItemController::class, 'home'])->name('items.home');
Route::get('/filter', [ItemController::class, 'filter'])->name('items.filter');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/select-category', [ItemController::class, 'selectCategory'])->name('items.selectCategory');
Route::post('/items/select-category', [ItemController::class, 'storeCategorySelection']);
Route::get('/items/select-color', [ItemController::class, 'selectColor'])->name('items.selectColor');
Route::post('/items/store-color-selection', [ItemController::class, 'storeColorSelection'])->name('items.storeColorSelection');
Route::get('/items/clear-selected-colors', function () {
    session()->forget('selected_colors');
    return redirect()->route('items.selectColor');
})->name('items.clearSelectedColors');
Route::post('/items/store-category-selection', [ItemController::class, 'storeCategorySelection'])->name('items.storeCategorySelection');
// いま入力している内容をセッションに保存するAPI
Route::post('/items/save-draft', [ItemController::class, 'saveDraft'])->name('items.saveDraft');
