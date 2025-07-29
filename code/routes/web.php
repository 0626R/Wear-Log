<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\MypageController;


// "/" にアクセスしたら、ItemController の home メソッドを呼ぶ
Route::get('/', [ItemController::class, 'home'])->name('items.home');
Route::get('/filter', [ItemController::class, 'home'])->name('items.filter');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
