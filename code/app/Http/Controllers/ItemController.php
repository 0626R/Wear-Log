<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Color;

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

    // 洋服新規登録
    public function create()
    {
        $selectedColorIds = session('selected_colors', []);
        $selectedColors = Color::whereIn('id', $selectedColorIds)->get();
        return view('items.create', compact('selectedColors'));
    }


    public function selectCategory()
    {
        $categories = ['トップス','ボトムス','ワンピース','アウター','セットアップ','バッグ','シューズ','アクセサリー','ファッション雑貨','その他'];
        return view('items.select-category', compact('categories'));
    }

    public function storeCategorySelection(Request $request)
    {
        session(['selected_category' => $request->input('categories', [])]);
        return redirect()->route('items.create');
    }

    public function selectColor()
    {
        $colors = Color::all(); // ← DBから取得（IDも含む）
        return view('items.select-color', compact('colors'));
    }

    public function store(Request $request)
    {
        $item = Item::create([
            'category'      => session('selected_category'),
            'brand'         => $request->input('brand'),
            'price'         => $request->input('price'),
            'season'        => $request->input('season'),
            'purchased_at'  => $request->input('purchased_at'),
            'status'        => $request->input('status'),
            'memo'          => $request->input('memo'),
        ]);

        if ($request->has('colors')) {
            $item->colors()->sync($request->input('colors')); // 多対多リレーション
        }

        return redirect()->route('items.home')->with('success', '登録完了');
    }

    public function storeColorSelection(Request $request)
    {
        session(['selected_colors' => $request->input('colors', [])]); // ← IDの配列が入る
        return redirect()->route('items.create');
    }

}

