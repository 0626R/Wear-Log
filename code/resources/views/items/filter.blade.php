@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>並び替え・絞り込み</h4>

    {{-- 並び替えセクション --}}
    <div class="mt-3">
        <h6>並び替え</h6>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sort" id="default" checked>
            <label class="form-check-label" for="default">通常</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sort" id="frequent">
            <label class="form-check-label" for="frequent">利用頻度高い</label>
        </div>

    </div>

    {{-- シーズンフィルター --}}
    <div class="mt-4">
        <h6>シーズン</h6>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="spring">
            <label class="form-check-label" for="spring">春</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="summer">
            <label class="form-check-label" for="summer">夏</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="autumn">
            <label class="form-check-label" for="summer">秋</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="winter">
            <label class="form-check-label" for="summer">冬</label>
        </div>
    </div>
</div>
@endsection
