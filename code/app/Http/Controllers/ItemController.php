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
        $draft            = session('item_draft', []);
        return view('items.create', compact('selectedColors'));
    }
    // ドラフト保存（選択画面へ行く前にJSから呼ぶ）
    public function saveDraft(Request $request)
    {

        // 保存したいフィールドだけ拾う
        $draft = $request->only(['brand','price','season','purchased_at','status','memo']);
        session(['item_form' => $draft]);
        return response()->noContent();

        // session([
        //     'item_draft' => $request->only([
        //         'brand','price','season','purchased_at','status','memo'
        //     ])
        // ]);
        // return response()->noContent();
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
            'image'        => ['nullable','image','max:5120'], // ← name="image" に対応
        ]);
    
        // 2) 外部キー
        $data['user_id'] = auth()->id() ?? 1;
    
        // 3) 画像アップロード（ここで $data['image'] をセット）
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public'); // items/xxxx.jpg
        }
    
        // 4) 作成は「必ず」ここ。これより前に create() を呼ばない。
        $item = \App\Models\Item::create($data);
    
        // （任意）カテゴリ
        $selectedName = collect(session('selected_category', []))->first();
        if ($selectedName) {
            $categoryId = \App\Models\Category::where('name', $selectedName)->value('id');
            if ($categoryId) {
                $item->categories()->sync([$categoryId]);
            }
        }
        $colorIds = $request->input('colors', session('selected_colors', []));
        if (!empty($colorIds)) {
            $item->colors()->sync($colorIds);
        }
    
        // 使い終わったらサーバ側の下書きと選択状態をクリア
        session()->forget(['item_form','item_draft','selected_category','selected_colors']); // ← 追加
        return redirect()->route('items.home')->with('success','登録完了');

    }


    public function storeColorSelection(Request $request)
    {
        session(['selected_colors' => $request->input('colors', [])]); // 送られてきた colors[]（IDの配列）をセッションに保存
        return redirect()->route('items.create');
    }

    // カテゴリ選択画面へ行く前に現在の入力値を保存
    public function goToCategorySelection(Request $request)
    {
        session()->put('item_form', $request->all());
        return redirect()->route('items.selectCategory');
    }


}

