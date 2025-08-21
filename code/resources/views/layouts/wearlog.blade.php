<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Wear Log</title>

  {{-- 必要なら Bootstrap/CSS をここで読み込む --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('head')
</head>
<body>
  {{-- ここにはロゴやBreezeのナビは置かない。画面だけを描画 --}}
  @yield('content')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')

  @include('components.wearlog-footer')

</body>
</html>
