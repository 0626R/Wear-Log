@extends('layouts.app')

@section('content')
<div class="container">
    <h4>カテゴリ選択</h4>
    @php $selected = session('selected_category', []); @endphp

    <form method="POST" action="{{ route('items.storeCategorySelection') }}">
    @csrf
    {{-- @foreach ($categories as $i => $category)
        @php $id = 'cat_'.$i; @endphp
        <div class="form-check mb-2">
        <input
            type="checkbox"
            class="form-check-input"
            name="categories[]"
            id="{{ $id }}"
            value="{{ $category }}"
            {{ in_array($category, (array)$selected, true) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="{{ $id }}">{{ $category }}</label>
        </div>
    @endforeach --}}
    @foreach($categories as $cat)
    <div class="form-check">
        <input type="checkbox" class="form-check-input"
            id="cat_{{ $cat->id }}" name="categories[]"
            value="{{ $cat->id }}" @checked(in_array($cat->id, $selected))>
        <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
    </div>
    @endforeach

    <button type="submit" class="btn btn-dark mt-3">選択して戻る</button>
    </form>
</div>
@endsection
