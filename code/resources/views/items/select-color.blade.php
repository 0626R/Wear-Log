{{-- @extends('layouts.app')

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
@endsection --}}


@extends('layouts.wearlog')

@section('content')
<div class="container mt-3">
  <h4>カラーを選択</h4>

  <form action="{{ route('items.storeColorSelection') }}" method="POST">
    @csrf

    <div class="list-group mt-3">
      @foreach ($colors as $color)
        <label class="d-block mb-2">
          <input type="checkbox"
                name="colors[]"
                value="{{ $color->id }}"
                {{ in_array($color->id, $selected ?? []) ? 'checked' : '' }}>
          {{ $color->name }}
        </label>
      @endforeach
    </div>

    <div class="d-flex gap-2 mt-3">
      <a href="{{ route('items.create') }}" class="btn btn-outline-secondary flex-fill">キャンセル</a>
      <button type="submit" class="btn btn-dark flex-fill">決定して戻る</button>
    </div>

    {{-- クリア機能（全部外したい時） --}}
    <div class="text-end mt-2">
      <a href="{{ route('items.clearSelectedColors') }}" class="small text-decoration-none">選択をクリア</a>
    </div>
  </form>
</div>

    {{-- フッターに被らないためのスペース --}}
    <div style="height: 96px;"></div>

@endsection