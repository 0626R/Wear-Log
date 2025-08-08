@extends('layouts.app')

@section('content')
<div class="container">
    <h4>カテゴリ選択</h4>
    <form method="POST" action="{{ route('items.selectCategory') }}">
        @csrf
        @foreach($categories as $category)
            <div class="form-check">
                <input type="checkbox" name="categories[]" value="{{ $category }}" class="form-check-input" id="{{ $category }}">
                <label class="form-check-label" for="{{ $category }}">{{ $category }}</label>
            </div>
        @endforeach
        <button type="submit" class="btn btn-dark mt-3">選択して戻る</button>
    </form>
</div>
@endsection
