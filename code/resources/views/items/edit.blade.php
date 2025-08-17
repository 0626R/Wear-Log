@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-5">
  <h3 class="mb-3">アイテム編集</h3>

  <form action="{{ route('items.update',$item) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    @include('items.form', [
      'item' => $item,
      'colors' => $colors,
      'selectedColors' => $selectedColors,
    ])

    {{-- <div class="text-center mt-4">
      <button type="submit" class="btn btn-dark px-5">更新</button>
    </div>
  </form> --}}

    {{-- フッターに被らないためのスペース --}}
    <div style="height: 96px;"></div>

</div>
@endsection
