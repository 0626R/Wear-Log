<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'WearLog')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Bootstrap CDNやCSSの読み込み --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-bottom { position: fixed; bottom: 0; width: 100%; background: #fff; }
        .fab { position: fixed; bottom: 80px; right: 20px; background: #d97e75; border-radius: 50%; padding: 15px; color: white; }
    </style>
</head>
<body>
    @yield('content')

    {{-- 共通ナビゲーションバー --}}
    <nav class="navbar navbar-light navbar-bottom justify-content-around">
        <a href="#"><i class="bi bi-house-fill"></i><br>ホーム</a>
        <a href="#"><i class="bi bi-calendar-event"></i><br>カレンダー</a>
        <a href="#"><i class="bi bi-bar-chart-fill"></i><br>統計</a>
        <a href="#"><i class="bi bi-three-dots"></i><br>その他</a>
    </nav>

    {{-- フローティングボタン --}}
    <a href="#" class="fab"><i class="bi bi-camera-fill"></i></a>

    {{-- Bootstrap JS読み込み（必要であれば） --}}
</body>
</html>
