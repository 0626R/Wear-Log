@extends('layouts.wearlog')

@section('content')
<form method="GET" action="{{ route('items.home') }}" id="filterForm" class="flt-wrap">

    <div class="flt-top">
        <a href="{{ url()->previous() }}" class="back">‹</a>
        <h2 class="text-center mb-2">並び替え・絞り込み</h2>
        <div></div>
    </div>
    <hr class="mb-4" style="width: 100%; height: 2px; background-color: black; margin: 0 auto;">

    @php
        $sort   = request('sort','normal');
        $pickedSeasons = (array) request('seasons',[]);
        $pickedColors  = (array) request('colors',[]);
    @endphp

{{-- セクション：並び替え --}}
<div class="sect">
    <h6>並び替え</h6>
  <label><input type="radio" name="sort" value="normal"       {{ $sort=='normal' ? 'checked' : '' }}> 通常</label><br>
  <label><input type="radio" name="sort" value="usage_high"   {{ $sort=='usage_high' ? 'checked' : '' }}> 利用頻度高い</label><br>
  <label><input type="radio" name="sort" value="usage_low"    {{ $sort=='usage_low' ? 'checked' : '' }}> 利用頻度低い</label><br>
  <label><input type="radio" name="sort" value="created_desc" {{ $sort=='created_desc' ? 'checked' : '' }}> 登録日新しい</label><br>
  <label><input type="radio" name="sort" value="created_asc"  {{ $sort=='created_asc' ? 'checked' : '' }}> 登録日古い</label>

  <hr>

  {{-- シーズン（例） --}}
  <h6>シーズン</h6>
  @foreach(['spring'=>'春','summer'=>'夏','autumn'=>'秋','winter'=>'冬','all_year'=>'通年'] as $k => $label)
    <label><input type="checkbox" name="seasons[]" value="{{ $k }}"
           {{ in_array($k, $pickedSeasons) ? 'checked' : '' }}> {{ $label }}</label><br>
  @endforeach

  {{-- カラー（例） --}}
  <h6 class="mt-3">カラー</h6>
    @php $pickedColors = (array) request('colors',[]); @endphp
    @foreach ($colors as $c)   {{-- ← コントローラから渡された $colors を使用 --}}
    <label class="d-block">
        <input type="checkbox" name="colors[]" value="{{ $c->id }}"
            {{ in_array($c->id, $pickedColors) ? 'checked' : '' }}>
        {{ trim($c->name) }}
    </label>
    @endforeach

  <div class="mt-3">
    <button class="btn btn-primary">適用</button>
    <a href="{{ route('items.home') }}" class="btn btn-outline-secondary">クリア</a>
  </div>
</form>

<style>
/* ====== スタイル（軽量CSSでスマホUIっぽく） ====== */
.flt-wrap{max-width:720px;margin:0 auto;padding-bottom:90px}
.flt-top{position:sticky;top:0;z-index:10;display:flex;align-items:center;justify-content:space-between;background:#fff;padding:14px 16px;border-bottom:1px solid #eee}
.flt-top .back{font-size:28px;text-decoration:none;color:#333}
.flt-top .ttl{font-size:22px;font-weight:700}
.flt-top .done{border:0;background:#19764b;color:#fff;padding:8px 14px;border-radius:20px;font-weight:700}

.sect{margin-top:14px;background:#fff}
.sect-hd{background:#f2f2f4;color:#666;padding:10px 16px;font-weight:700;letter-spacing:.05em}
.row{display:flex;align-items:center;justify-content:space-between;padding:16px;border-bottom:1px solid #eee;font-size:18px}
.row input[type=checkbox], .row input[type=radio]{transform:scale(1.4)}
.row:last-child{border-bottom:0}

.flt-bar{position:fixed;left:0;right:0;bottom:0;display:flex;gap:12px;justify-content:center;background:#fff;border-top:1px solid #eee;padding:12px}
.flt-bar .reset{display:inline-block;padding:10px 18px;border-radius:24px;background:#f0f0f0;color:#333;text-decoration:none;font-weight:600}
.flt-bar .apply{padding:10px 24px;border-radius:24px;border:0;background:#19764b;color:#fff;font-weight:700}
</style>
@endsection