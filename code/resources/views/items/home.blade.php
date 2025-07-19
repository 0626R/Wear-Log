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

    {{-- 天気情報 --}}
    <div class="bg-light rounded p-2 d-flex justify-content-between align-items-center mb-3">
        <span>13(金)</span>
        <span class="text-danger">☀️ 23°C</span><span class="text-info">/13°C</span>
        <span class="badge bg-success">仙台</span>
    </div>

    {{-- カテゴリ --}}
    <div class="d-flex overflow-auto mb-3">
        @foreach(['すべて', 'バッグ', 'アウター', 'シューズ', 'トップス'] as $category)
            <span class="me-3">{{ $category }}</span>
        @endforeach
    </div>

    {{-- 商品カード --}}
    <div class="row">
        @foreach($items as $item)
        <div class="col-4 mb-3">
            <div class="card shadow-sm rounded">
                <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->name }}">
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
