@extends('layouts.wearlog')

@section('content')
<div class="container mt-4 pb-5">
    <h2 class="text-center mb-2">アイテム詳細</h2>
    <hr class="mb-4" style="width: 100%; height: 2px; background-color: black; margin: 0 auto;">


  <div class="row g-4">
    <div class="col-md-4 text-center">
      <img id="preview" class="img-fluid rounded"
           src="{{ $item->image ? asset('storage/'.$item->image) : asset('images/icon_camera.png') }}"
           alt="">
    </div>
    <div class="col-md-8">
      <dl class="row">
        <dt class="col-sm-3">カテゴリ</dt><dd class="col-sm-9">{{ $item->categories->pluck('name')->join(' / ') }}</dd>
        <dt class="col-sm-3">ブランド</dt><dd class="col-sm-9">{{ $item->brand }}</dd>
        <dt class="col-sm-3">カラー</dt><dd class="col-sm-9">{{ $colors->join(' / ') }}</dd>
        <dt class="col-sm-3">購入価格</dt><dd class="col-sm-9">{{ $item->price }}</dd>
        <dt class="col-sm-3">シーズン</dt><dd class="col-sm-9">{{ $item->season }}</dd>
        <dt class="col-sm-3">購入日</dt><dd class="col-sm-9">{{ optional($item->purchased_at)->format('Y-m-d') }}</dd>
        <dt class="col-sm-3">着用回数</dt><dd class="col-sm-9">{{ number_format($item->wear_count ?? $item->worn_count ?? 0) }}回</dd>
        <dt class="col-sm-3">出品状況</dt><dd class="col-sm-9">{{ $item->status }}</dd>
        <dt class="col-sm-3">メモ</dt><dd class="col-sm-9">{!! nl2br(e($item->memo)) !!}</dd>
      </dl>
    </div>
  </div>
  <div class="d-flex justify-content-center align-items-center mb-3">
    <div>
      <a href="{{ route('items.edit',$item) }}" class="btn btn-outline-primary me-2">編集</a>
      <button id="btnDelete" class="btn btn-outline-danger">削除</button>
    </div>
  </div>

</div>

<script>
document.getElementById('btnDelete').addEventListener('click', async () => {
  if(!confirm('削除しますか？')) return;
  const res = await fetch('{{ route('items.destroy',$item) }}', {
    method: 'DELETE',
    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
  });
  if (res.ok) location.href = '{{ route('items.home') }}';
  else alert('削除に失敗しました');
});
</script>
@endsection