<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


// "/" にアクセスしたら、ItemController の home メソッドを呼ぶ
Route::get('/', [ItemController::class, 'home'])->name('items.home');
Route::get('/filter', [ItemController::class, 'home'])->name('items.filter');