<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


// "/" にアクセスしたら、ItemController の home メソッドを呼ぶ
Route::get('/', [ItemController::class, 'home']);