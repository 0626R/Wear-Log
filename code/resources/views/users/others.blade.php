@extends('layouts.wearlog')

@section('content')
<div class="container">
    <h2>会員情報</h2>

    @if ($type === 'admin')
        <dl class="row">
            <dt class="col-sm-3">権限</dt><dd class="col-sm-9">管理者</dd>
            <dt class="col-sm-3">名前</dt><dd class="col-sm-9">{{ $actor->name }}</dd>
            <dt class="col-sm-3">メール</dt><dd class="col-sm-9">{{ $actor->email }}</dd>
        </dl>
    @else
        <dl class="row">
            <dt class="col-sm-3">権限</dt><dd class="col-sm-9">{{ $actor->is_premium ? 'プレミアム会員' : '一般会員' }}</dd>
            <dt class="col-sm-3">氏名</dt>
            <dd class="col-sm-9">{{ $actor->last_name ?? '' }} {{ $actor->first_name ?? '' }}</dd>
            <dt class="col-sm-3">フリガナ</dt>
            <dd class="col-sm-9">{{ $actor->last_name_kana ?? '' }} {{ $actor->first_name_kana ?? '' }}</dd>
            <dt class="col-sm-3">メール</dt><dd class="col-sm-9">{{ $actor->email }}</dd>
        </dl>
    @endif

    <form action="{{ route('logout') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-danger">ログアウト</button>
    </form>
</div>
@endsection