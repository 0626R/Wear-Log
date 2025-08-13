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
        // 選択されたカテゴリ（なければ「すべて」）
        $selectedCategory = $request->input('category', 'すべて');

        // アイテム取得（カテゴリで絞り込み）
    $items = \App\Models\Item::with('categories')
        ->when($selectedCategory !== 'すべて', function ($q) use ($selectedCategory) {
            $q->whereHas('categories', function ($qq) use ($selectedCategory) {
                $qq->where('name', $selectedCategory);
            });
        })
        ->get();

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
        $selected = session('selected_colors', []); // 前回の選択(ID配列)を取り出す
        return view('items.select-color', compact('colors', 'selected'));
    }

    public function store(\Illuminate\Http\Request $request)
    {

       // 1) バリデーション
        $data = $request->validate([
            'brand'        => ['nullable','string','max:50'],
            'price'        => ['nullable','integer','min:0'],
            'season'       => ['nullable','in:春,夏,秋,冬'],
            'purchased_at' => ['nullable','date'],
            'status'       => ['required','in:出品しない,出品する,検討中'],
            'memo'         => ['nullable','string','max:1000'],
            'image'        => ['nullable','image','max:5120'],
        ]);

        // // 2) カテゴリはセッションから
        // $data['category'] = session('selected_category');
        $data['category'] = null;
        unset($data['category']);

        // 必須：外部キー
        $data['user_id'] = auth()->id() ?? 1;

        // 3) 画像アップロード（storage/app/public/items に保存）
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        // 検証用
        // dd([
        //     'hasFile' => $request->hasFile('image'),
        //     'file'    => $request->file('image'),
        //     'all'     => $request->all(),  // 他の値も見える
        // ]);
    

        // 4) アイテム作成
        $item = \App\Models\Item::create($data);

         // カテゴリ（pivot）
        $selectedName = collect(session('selected_category', []))->first(); // 文字列ならそのまま
        // $categoryId   = \App\Models\Category::where('name', $selectedName)->value('id');

        if ($selectedName) {
            $categoryId = \App\Models\Category::where('name', $selectedName)->value('id');
            if ($categoryId) {
                $item->categories()->sync([$categoryId]);
            }
        }

        // 5) カラー（多対多）
        $colorIds = $request->input('colors', session('selected_colors', []));
        if (!empty($colorIds)) {
            $item->colors()->sync($colorIds);
        }

        // 6) セッションクリア
        session()->forget(['selected_category','selected_colors']);

        return redirect()->route('items.home')->with('success', '登録完了');
        }

    public function storeColorSelection(Request $request)
    {
        session(['selected_colors' => $request->input('colors', [])]); // 送られてきた colors[]（IDの配列）をセッションに保存
        return redirect()->route('items.create');
    }

}

