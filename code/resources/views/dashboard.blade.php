@extends('layouts.admin')

@section('content')
<div class="container mt-4 pb-5 admin-wrap">
  <h2 class="page-title mb-3">ユーザー管理</h2>
  <hr class="mb-4 separator">

  <div class="card shadow-sm border-0">
    <div class="table-responsive admin-table">
      <table class="table mb-0">
        <thead>
          <tr>
            <th class="text-center" style="width:80px;">ID</th>
            <th style="min-width:200px;">名前</th>
            <th>Email</th>
            <th style="width:200px;">登録日</th>
            <th class="text-center" style="width:110px;">操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $u)
            <tr>
              <td class="text-center text-muted">{{ $u->id }}</td>
              <td class="name-cell">
                <span class="name-link">
                  {{ trim(($u->last_name ?? '').' '.($u->first_name ?? '')) ?: ($u->name ?? '-') }}
                </span>
              </td>
              <td class="text-break">{{ $u->email }}</td>
              <td>{{ $u->created_at?->format('Y-m-d H:i:s') }}</td>
              <td class="text-center">
                <form method="POST" action="{{ route('admin.users.destroy',$u) }}"
                      onsubmit="return confirm('ID:{{ $u->id }} を削除しますか？');" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm px-3 rounded-pill">削除</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">ユーザーはいません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="logout-wrap text-center">
    <form action="{{ route('logout') }}" method="POST" class="d-inline-block m-0">
      @csrf
      <button type="submit" class="btn btn-danger btn-lg px-5 rounded-pill logout-btn">
        ログアウト
      </button>
    </form>
</div>

<style>
  /* コンテナを中央寄せ */
  .admin-wrap{ max-width:1100px; margin:0 auto; padding-bottom:96px; }

  /* タイトルを中央＋太字 */
  .page-title{ text-align:center; font-size:32px; font-weight:800; letter-spacing:.02em; }

  /* タイトル下の細いグレーライン */
  .separator{ width:92%; height:2px; background:#cfcfcf; margin:0 auto; }

  /* 表そのものも中央寄せ（幅を少し絞る） */
  .admin-table .table{ width:92%; margin:0 auto; }

  /* ヘッダー：グレー背景＋黒文字 */
  .admin-table thead th{
    background:#e9ecef; color:#000; font-weight:700;
    border-bottom:2px solid #000;
    /* 多数行でもヘッダ固定にしたいなら↓を有効化
    position: sticky; top: 0; z-index: 1;
    */
  }

  /* 行区切り＆うっすら縞 */
  .admin-table tbody tr{ border-bottom:1px solid #e6e6e6; }
  .admin-table tbody tr:nth-of-type(odd){ background:#fafbfc; }
  .admin-table td, .admin-table th{ padding:.9rem .85rem; vertical-align:middle; }

  /* 名前をリンク風 */
  .name-link{ color:#0d6efd; text-decoration:underline; font-weight:600; }

  /* “赤い削除ボタン”を強制（Bootstrapが効いてなくても赤く） */
  .btn-danger{
    background:#dc3545 !important;
    border-color:#dc3545 !important;
    color:#fff !important;
  }
  .btn-danger:hover{ filter:brightness(.95); }
  .separator{ width:100%; height:2px; background:#cfcfcf; border:0; margin:0 auto 22px; }
  /* 名前はただの黒文字に（リンクでも下線なし） */
.admin-table .name-cell a,
.admin-table .name-link{
  color:#212529 !important;   /* 黒(bootstrapの本文色) */
  text-decoration:none !important;
  font-weight:550;
}
.admin-table .name-cell a:hover{
  color:#212529;              /* ホバーでも色を変えない */
  text-decoration:none;
}


</style>
@endsection
