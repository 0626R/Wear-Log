<h1>アイテム一覧</h1>
<ul>
  @foreach ($items as $item)
    <li>{{ $item->brand }} ({{ $item->season }})</li>
  @endforeach
</ul>
