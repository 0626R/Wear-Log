<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function filter()
    {
        return view('items.filter');
    }

    public function home(Request $request)
    {
        $selectedCategory = $request->input('category', 'すべて');
        $items = Item::when($selectedCategory !== 'すべて', function ($query) use ($selectedCategory) {
            return $query->where('category', $selectedCategory);
        })->get();

        return view('items.home', compact('items', 'selectedCategory'));
    }
}

