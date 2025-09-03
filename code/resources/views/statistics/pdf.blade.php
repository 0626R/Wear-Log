@php
// PDFはURLより実ファイルパスが良い（DomPDF）
function img_path($item) {
  return $item->image ? public_path('storage/'.$item->image) : null;
}
@endphp
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
  /* 日本語は DejaVu Sans でOK。崩れる場合は NotoSansJP を同梱して @font-face してください。 */
  body{ font-family: DejaVu Sans, sans-serif; font-size:11px; }
  h2{ margin:0 0 8px; }
  table{ width:100%; border-collapse:collapse; }
  th,td{ border:1px solid #ccc; padding:6px; vertical-align:top; }
  th{ background:#f2f2f2; }
  .thumb{ height:60px; width:auto; }
  .muted{ color:#666; }
  .box{ display:inline-block; width:11px; height:11px; border:1px solid #333; margin-right:4px; }
</style>
</head>
<body>

  <table>
    <thead>
      <tr>
        <th style="width:70px;">写真</th>
        <th>ブランド / カテゴリ / カラー</th>
        <th style="width:80px;">価格</th>
        <th style="width:120px;">登録日</th>
        <th style="width:180px;">チェック</th>
      </tr>
    </thead>
    <tbody>
    @foreach($items as $it)
      <tr>
        <td>
          @if(img_path($it))
            <img src="{{ img_path($it) }}" class="thumb">
          @endif
        </td>
        <td>
          <div><strong>{{ $it->brand ?: '—' }}</strong></div>
          <div class="muted">{{ $it->categories->pluck('name')->join(' / ') }}</div>
          <div class="muted">カラー：{{ $it->colors->pluck('name')->join(' / ') }}</div>
        </td>
        <td>{{ $it->price ? number_format($it->price) : '—' }}</td>
        <td>{{ optional($it->created_at)->format('Y-m-d') }}</td>
        <td>
          <span class="box"></span>撮影　
          <span class="box"></span>状態確認　
          <span class="box"></span>採寸　<br>
          <span class="box"></span>出品　
          <span class="box"></span>発送
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</body>
</html>


<style>
  @font-face{
    font-family:'NotoSansJP';
    font-weight:400;
    src:url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Regular.ttf') }}') format('truetype'),
        url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Regular.otf') }}') format('opentype');
  }
  @font-face{
    font-family:'NotoSansJP';
    font-weight:700;
    src:url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Bold.ttf') }}') format('truetype'),
        url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Bold.otf') }}') format('opentype');
  }
  html,body,*{ font-family:'NotoSansJP','DejaVu Sans',sans-serif; }
  
  
  <style>
@font-face{
  font-family: 'NotoSansJP';
  font-style: normal;
  font-weight: 400;
  src: url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Regular.ttf') }}') format('truetype'),
       url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Regular.otf') }}')  format('opentype');
}
@font-face{
  font-family: 'NotoSansJP';
  font-style: normal;
  font-weight: 700;
  src: url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Bold.ttf') }}')    format('truetype'),
       url('{{ public_path('fonts/NotoSansJP/NotoSansJP-Bold.otf') }}')    format('opentype');
}
html, body, * { font-family: 'NotoSansJP', 'DejaVu Sans', sans-serif; }
</style>


  </style>
    