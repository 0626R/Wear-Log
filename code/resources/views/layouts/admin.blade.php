<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>管理</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    /* テーブルの見た目を「下線区切り」に寄せる */
    .u-table thead th{border-bottom:2px solid #000;}
    .u-table tbody tr{border-bottom:1px solid #e9ecef;}
  </style>
</head>
<body class="bg-light">
  @yield('content')
</body>
</html>
