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
    {{-- ↓home.blade.php）の内容がここに入る --}}
    @yield('content')

    {{-- 共通ナビゲーションバー（フッター） --}}
    <nav class="footer-nav">
        <a href="{{ route('items.home') }}" class="footer-link">
            <img src="{{ asset('images/icon_home.png') }}" alt="ホーム">
            ホーム
        </a>
        <a href="{{ route('calendar') }}" class="footer-link">
            <img src="{{ asset('images/icon_calendar.png') }}" alt="カレンダー">
            カレンダー
        </a>
        <a href="{{ route('statistics') }}" class="footer-link">
            <img src="{{ asset('images/icon_graph.png') }}" alt="統計">
            統計
        </a>
        <a href="{{ route('mypage') }}" class="footer-link">
            <img src="{{ asset('images/icon_mypage.png') }}" alt="マイページ">
            その他
        </a>
    </nav>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <a href="{{ route('items.create') }}" class="fab">
        <img src="{{ asset('images/icon_camera.png') }}" alt="洋服登録" style="height: 28px;">
    </a>
    

</body>
</html>

<style>
    .footer-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: white;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-around;
        padding: 8px 0;
        z-index: 1000;
    }

    .footer-link {
        text-align: center;
        text-decoration: none;
        color: #b65e59; /* くすみピンク系 */
        font-size: 13px;
    }

    .footer-link img {
        display: block;
        margin: 0 auto 4px;
        height: 35px;
    }

    .footer-link:hover {
        color: #a54f4a;
    }

    .fab {
        position: fixed;
        bottom: 60px;
        right: 20px;
        background-color: #d97e75;
        border-radius: 50%;
        padding: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        z-index: 1100;
    }
</style>
