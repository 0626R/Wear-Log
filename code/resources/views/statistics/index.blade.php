@extends('layouts.wearlog')

@section('content')
<div class="container mt-4 pb-5">
  <h2 class="text-center mb-2">抽出</h2>
  <hr class="mb-4" style="width:100%;height:2px;background:#000;margin:0 auto;">

  <div class="text-end mb-3">
    <a class="btn btn-primary" href="{{ route('statistics.export.pdf') }}">
      出品チェックリストPDFを作成
    </a>
  </div>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead class="table-light">
        <tr>
          <th style="width:80px;">画像</th>
          <th>ブランド / カテゴリ</th>
          <th>カラー</th>
          <th style="width:120px;">購入価格</th>
          <th style="width:140px;">登録日</th>
        </tr>
      </thead>
      <tbody>
      @foreach($items as $it)
        <tr>
          <td>
            @if($it->image)
              <img src="{{ asset('storage/'.$it->image) }}" style="height:60px;width:auto;border-radius:6px;">
            @endif
          </td>
          <td>
            <div class="fw-semibold">{{ $it->brand ?: '—' }}</div>
            <div class="text-muted small">{{ $it->categories->pluck('name')->join(' / ') }}</div>
          </td>
          <td class="text-muted small">{{ $it->colors->pluck('name')->join(' / ') }}</td>
          <td>{{ $it->price ? number_format($it->price) : '—' }}</td>
          <td>{{ optional($it->created_at)->format('Y-m-d') }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>
</div>
@endsection
