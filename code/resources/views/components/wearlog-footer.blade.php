<nav class="wl-footer">
    <a href="{{ route('items.home') }}" class="wl-link">
      <img src="{{ asset('images/icon_home.png') }}" alt="ホーム">
      ホーム
    </a>
  
    <a href="{{ route('calendar') }}" class="wl-link">
      <img src="{{ asset('images/icon_calendar.png') }}" alt="カレンダー">
      カレンダー
    </a>
  
    <a href="{{ route('statistics') }}" class="wl-link">
      <img src="{{ asset('images/icon_graph.png') }}" alt="統計">
      統計
    </a>
  
    <a href="{{ route('mypage') }}" class="wl-link">
      <img src="{{ asset('images/icon_mypage.png') }}" alt="その他">
      その他
    </a>
  </nav>
  
  <a href="{{ route('items.create') }}" class="wl-fab" aria-label="新規登録">
    <img src="{{ asset('images/icon_camera.png') }}" alt="" style="height:28px;">
  </a>
  
  <style>
    .wl-footer{
      position: fixed; left:0; right:0; bottom:0; z-index:1000;
      display:flex; justify-content:space-around; align-items:center;
      padding:8px 0; background:#fff; border-top:1px solid #ddd;
    }
    .wl-link{ text-align:center; text-decoration:none; color:#b65e59; font-size:13px; }
    .wl-link img{ display:block; margin:0 auto 4px; height:35px; }
    .wl-link:hover{ color:#a54f4a; }
  
    .wl-fab{
      position: fixed; right:20px; bottom:68px; z-index:1100;
      display:flex; justify-content:center; align-items:center;
      width:56px; height:56px; border-radius:50%;
      background:#d97e75; color:#fff; box-shadow:0 4px 10px rgba(0,0,0,.2);
    }
  </style>