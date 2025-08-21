{{-- @extends('layouts.guest')

@section('content') --}}
<x-guest-layout>

<div class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md bg-white shadow rounded p-6">
    <h1 class="text-xl font-bold mb-4">ログイン</h1>

    @if(session('status'))
      <div class="mb-3 text-green-700">{{ session('status') }}</div>
    @endif
    @error('email')
      <div class="mb-3 text-red-600">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('login.attempt') }}">
      @csrf
      <label class="block mb-2 text-sm">メールアドレス</label>
      <input name="email" type="email" class="w-full border rounded px-3 py-2 mb-4"
             value="{{ old('email') }}" required autofocus>

      <label class="block mb-2 text-sm">パスワード</label>
      <input name="password" type="password" class="w-full border rounded px-3 py-2 mb-4" required>

      <label class="inline-flex items-center mb-4">
        <input type="checkbox" name="remember" class="mr-2">ログイン状態を保持
      </label>

      <button class="w-full bg-black text-white py-2 rounded">ログインする</button>
    </form>

    <div class="mt-6 text-center">
      はじめてご利用ですか？
      <a class="text-blue-600 underline" href="{{ route('register') }}">新規会員登録</a>
    </div>
  </div>
</div>
{{-- @endsection --}}
</x-guest-layout>