<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function home()
    {
        $items = Item::all();
        return view('items.home', compact('items'));
    }
    
    public function filter()
    {
        return view('items.filter');
    }
}

