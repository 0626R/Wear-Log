@extends('layouts.app')
@section('content')
<div class="container">
  <h2>ユーザー一覧</h2>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <table class="table">
    <thead>
      <tr>
        <th>ID</th><th>氏名</th><th>Email</th><th>プレミアム</th><th>登録日</th><th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->last_name ?? '' }} {{ $u->first_name ?? '' }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->is_premium ? 'はい' : 'いいえ' }}</td>
        <td>{{ $u->created_at }}</td>
        <td>
          <form method="POST" action="{{ route('admin.users.destroy',$u) }}"
                onsubmit="return confirm('削除してよろしいですか？');">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">削除</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->links() }}
</div>
@endsection
