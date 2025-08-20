@extends('layouts.app')

@section('content')
<div class="container">
  <h3 class="mb-4">管理画面｜ユーザー一覧</h3>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <table class="table align-middle">
    <thead>
      <tr>
        <th>ID</th><th>氏名/メール</th><th>プラン</th><th>登録日</th><th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>
          <div>{{ $u->name }}</div>
          <div class="text-muted small">{{ $u->email }}</div>
        </td>
        <td>
          @if($u->is_premium)
            <span class="badge bg-warning text-dark">プレミアム</span>
          @else
            <span class="badge bg-secondary">一般</span>
          @endif
        </td>
        <td>{{ $u->created_at?->format('Y-m-d H:i') }}</td>
        <td class="text-end">
          <form method="POST" action="{{ route('admin.users.destroy',$u) }}"
                onsubmit="return confirm('削除してよろしいですか？')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">削除</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->links() }}
</div>
@endsection


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
