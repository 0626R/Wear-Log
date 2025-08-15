@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- ヘッダー（ロゴ＋通知アイコンなど） --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- 左の空要素（スペース確保） --}}
        <div style="width: 40px;"></div>

       {{-- ロゴ画像 --}}
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo1.png') }}" alt="Wear Log ロゴ" style="height: 80px;">
        </div>

        {{-- 右のアイコン --}}
        <div>
            <a href="{{ route('items.filter') }}">
                <img src="{{ asset('images/filter_icon.png') }}" alt="フィルター" style="height: 50px;">
            </a>
        </div>
    </div>

    {{-- 天気情報APIにする --}}
    <div class="bg-light rounded p-2 d-flex justify-content-between align-items-center mb-3">
        <span>13(金)</span>
        <span class="text-danger">☀️ 23°C</span><span class="text-info">/13°C</span>
        <span class="badge bg-success">仙台</span>
    </div>

    {{-- カテゴリ --}}
    @php
        $categories = ['すべて','トップス','ボトムス','ワンピース','アウター','セットアップ','バッグ','シューズ','アクセサリー','ファッション雑貨','その他'];
        // ↓すべて」を初期値
        $selectedCategory = request()->input('category', 'すべて');
    @endphp

    {{-- カテゴリ選択で下線表示 --}}
    <div class="d-flex overflow-auto border-bottom mb-3 px-3">
        @foreach($categories as $category)
            <a href="{{ route('items.home', ['category' => $category]) }}"
            class="me-4 pb-2 text-decoration-none
            {{ $selectedCategory === $category 
                    ? 'border-bottom border-3 border-primary text-primary fw-bold' 
                    : 'text-secondary border-bottom border-0' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>


    {{-- 商品カード --}}
    <style>
        .item-grid{
          display:grid;
          grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); /* PCで7〜8枚並ぶ */
          gap:12px;
        }
        .item-card{
          width:100%;
          aspect-ratio:1/1;          /* 正方形 */
          overflow:hidden;
          border:1px solid #e5e5e5;
          border-radius:10px;
          background:#fff;
          box-shadow:0 1px 4px rgba(0,0,0,.04);
        }
        .item-card img{
          width:100%;
          height:100%;
          object-fit:cover;          /* はみ出しをトリミング */
          display:block;
        }
        .card{
          border-radius:12px;
          overflow:hidden;
          background:#fff;
          box-shadow:0 1px 6px rgba(0,0,0,.06)
        }
        .thumb{
            width:100%;
            aspect-ratio:1/1;object-fit:cover;
            display:block
        }
        
        @media (max-width:576px){ .item-grid{ grid-template-columns:repeat(3,1fr); } }
        @media (min-width:577px) and (max-width:992px){ .item-grid{ grid-template-columns:repeat(5,1fr); } }
      </style>
      
      <div class="item-grid">
        @foreach ($items as $item)
          <a class="card block" href="{{ route('items.show', $item) }}">
            <img src="{{ $item->image ? asset('storage/'.$item->image) : asset('images/no_image.png') }}"
                alt="{{ $item->brand }}" class="thumb"/>
          </a>
          {{-- <div class="item-card">
            @if ($item->image)
              <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->brand }}">
            @else
              <img src="{{ asset('images/no_image.png') }}" alt="no image">
            @endif
          </div> --}}
        @endforeach
      </div>
    {{-- フッターに被らないためのスペース --}}
    <div style="height: 96px;"></div>


</div>
@endsection

