@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mt-3">カラーを選択</h4>
    <form action="{{ route('items.storeColorSelection') }}" method="POST">
        @csrf
        @foreach ($colors as $color)
            <label>
                <input type="checkbox" name="colors[]" value="{{ $color->id }}">
                {{ $color->name }}
            </label>
        @endforeach
        <button type="submit">決定</button>
    </form>
</div>
@endsection
