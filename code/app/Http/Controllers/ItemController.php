<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Color;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;


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
    public function create(Request $request)
    {
        // セレクト画面から戻るときだけ flash('from_selector', true) を付けている前提
        $fromSelector = $request->session()->pull('from_selector', false);

        if (!$fromSelector) {
            // ふつうに /items/create を開いたときは下書きと選択状態をクリア
            $request->session()->forget([
                'item_form',
                'selected_categories',
                'selected_colors',
            ]);
        }

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
        // $categories = ['トップス','ボトムス','ワンピース','アウター','セットアップ','バッグ','シューズ','アクセサリー','ファッション雑貨','その他'];
        // return view('items.select-category', compact('categories'));
        $categories = Category::orderBy('name')->get(['id','name']);
        $selected   = session('selected_categories', []);  // int[]
        return view('items.select-category', compact('categories','selected'));
    }

    public function storeCategorySelection(Request $request)
    {
        // session(['selected_category' => $request->input('categories', [])]);
        $ids = collect($request->input('categories', []))
            ->map(fn($v)=>(int)$v)->filter()->values()->all();

        session([
            'selected_categories'   => $ids,
            'item_form.categories'  => $ids,  // 下書き復元用
        ]);

        return redirect()->route('items.create')->with('from_selector', true);
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
            // 'category'     => ['nullable','string','max:50'],
            'price'        => ['nullable','integer','min:0'],
            'season'       => ['nullable','in:春,夏,秋,冬'],
            'purchased_at' => ['nullable','date'],
            'status'       => ['required','in:出品しない,出品する,検討中'],
            'memo'         => ['nullable','string','max:1000'],
            'wear_count'   => ['nullable','integer','min:0'],
            'image'        => ['nullable','image','max:5120'],

            'categories'   => ['array'],
            'categories.*' => ['integer','exists:categories,id'],
            'colors'       => ['array'],
            'colors.*'     => ['integer','exists:colors,id'],
        ]);
    
        // 2) 外部キー
        $data['user_id'] = auth()->id() ?? 1;
        $data['wear_count'] = $data['wear_count'] ?? 0; // 未入力なら 0
    
        // 3) 画像アップロード（ここで $data['image'] をセット）
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public'); // items/xxxx.jpg
        }
    
        // ←create には pivot 配列を渡さない
        $categoryIds = $request->input('categories', session('selected_categories', []));
        $colorIds    = $request->input('colors',     session('selected_colors', []));
        unset($data['categories'], $data['colors']);

        // 4) 作成は「必ず」ここ。これより前に create() を呼ばない。
        $item = \App\Models\Item::create($data);
    
        // カテゴリ / カラーを pivot 保存
        if (!empty($categoryIds)) $item->categories()->sync($categoryIds);
        if (!empty($colorIds))    $item->colors()->sync($colorIds);

        // （任意）カテゴリ
        // $selectedName = collect(session('selected_category', []))->first();
        // if ($selectedName) {
        //     // $categoryId = \App\Models\Category::where('name', $selectedName)->value('id');
        //     // if ($categoryId) {
        //     //     $item->categories()->sync([$categoryId]);
        //     // }
        //     $categoryIds = $request->input('categories', session('selected_categories', []));
        //     if (!empty($categoryIds)) {
        //         $item->categories()->sync($categoryIds);
        //     }
        // }
        // $colorIds = $request->input('colors', session('selected_colors', []));
        // if (!empty($colorIds)) {
        //     $item->colors()->sync($colorIds);
        // }
    
        // 使い終わったらサーバ側の下書きと選択状態をクリア
        session()->forget(['item_form','item_draft','selected_category','selected_colors']); // ← 追加
        return redirect()->route('items.home')->with('success','登録完了');

    }


    public function storeColorSelection(Request $request)
    {
        session(['selected_colors' => $request->input('colors', [])]); // 送られてきた colors[]（IDの配列）をセッションに保存
        return redirect()->route('items.create')->with('from_selector', true);
    }

    // カテゴリ選択画面へ行く前に現在の入力値を保存
    public function goToCategorySelection(Request $request)
    {
        session()->put('item_form', $request->all());
        return redirect()->route('items.selectCategory');
    }


    public function show(Item $item)
    {
        // 編集画面へ
        $colors = $item->colors()->pluck('name'); // 表示用
        return view('items.show', compact('item', 'colors'));
    }

    public function edit(Item $item)
    {
        // 既存値をフォームに入れる。選択済みカラーもチェック済みにする
        $selectedColors = $item->colors()->pluck('id')->all();
        $colors = Color::all();
        $categories = Category::orderBy('name')->get(['id','name']);

        $selectedCategories = $item->categories()->pluck('id')->all();

        return view('items.edit', compact(
            'item', 'colors', 'selectedColors',
            'categories', 'selectedCategories'
        ));
    }

    public function update(\Illuminate\Http\Request $request, Item $item)
    {
        $data = $request->validate([
            'brand'        => ['nullable','string','max:50'],
            'price'        => ['nullable','integer','min:0'],
            'season'       => ['nullable','in:春,夏,秋,冬,通年'],
            'purchased_at' => ['nullable','date'],
            'status'       => ['required','in:出品しない,出品する,検討中'],
            'memo'         => ['nullable','string','max:1000'],
            'image'        => ['nullable','image','max:5120'],
            'colors'       => ['array'],
            'colors.*'     => ['integer'],
            'categories'   => ['array'],
            'categories.*' => ['integer','exists:categories,id'],

        ]);

        // 画像差し替え
        if ($request->hasFile('image')) {
            if ($item->image) Storage::disk('public')->delete($item->image);
            $data['image'] = $request->file('image')->store('items', 'public');
        } else {
            unset($data['image']);
        }

        $item->update($data);

        // カラー多対多
        $item->colors()->sync($request->input('colors', []));

        $item->categories()->sync($request->input('categories', []));


        return redirect()->route('items.show', $item)->with('success','更新しました');
    }

    public function destroy(Item $item)
    {
        // 画像削除
        if ($item->image) Storage::disk('public')->delete($item->image);
        // Pivotも自動で消える（外部キー/オンデリート設定が無いなら detach）
        $item->colors()->detach();
        $item->delete();

        // 非同期(AJAX)想定
        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('items.home')->with('success','削除しました');
    }
}

