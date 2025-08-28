@extends('layouts.wearlog')

@section('content')
<div class="container mt-4 pb-5" style="padding-bottom: 96px;">
    <h2 class="text-center mb-2">会員情報</h2>
    <hr class="mb-4" style="width: 100%; height: 2px; background-color: black; margin: 0 auto;">

    <div class="text-center">
    @if ($type === 'admin')
        <dl class="row g-2 w-auto mx-auto"> {{-- 中央寄せ＆余白ちいさめ --}}
        <dt class="col-4 text-end fw-semibold">権限</dt>
        <dd class="col-8 mb-1">管理者</dd>

        <dt class="col-4 text-end fw-semibold">名前</dt>
        <dd class="col-8 mb-1">{{ $actor->name }}</dd>

        <dt class="col-4 text-end fw-semibold">メール</dt>
        <dd class="col-8 mb-1">{{ $actor->email }}</dd>
        </dl>
        {{-- 一般ユーザー側↓ --}}
    @else
        <dl class="row g-2 w-auto mx-auto">
        <dt class="col-4 text-end fw-semibold">権限</dt>
        <dd class="col-8 mb-1">{{ $actor->is_premium ? 'プレミアム会員' : '一般会員' }}</dd>

        <dt class="col-4 text-end fw-semibold">氏名</dt>
        <dd class="col-8 mb-1">{{ $actor->last_name ?? '' }} {{ $actor->first_name ?? '' }}</dd>

        <dt class="col-4 text-end fw-semibold">フリガナ</dt>
        <dd class="col-8 mb-1">{{ $actor->last_name_kana ?? '' }} {{ $actor->first_name_kana ?? '' }}</dd>

        <dt class="col-4 text-end fw-semibold">メール</dt>
        <dd class="col-8 mb-1">{{ $actor->email }}</dd>
        </dl>
    @endif

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">ログアウト</button>
        </form>
    </div>
</div>
@endsection