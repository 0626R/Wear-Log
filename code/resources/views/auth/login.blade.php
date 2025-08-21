{{-- <!doctype html>
<html lang="ja">
<head><meta charset="utf-8"><title>管理者ログイン</title></head>
<body>
  <h1>管理者ログイン</h1>
  @if($errors->any()) <div style="color:red">{{ $errors->first() }}</div> @endif
  <form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <label>Email</label><input type="email" name="email" required><br>
    <label>Password</label><input type="password" name="password" required><br>
    <label><input type="checkbox" name="remember"> Remember</label><br>
    <button type="submit">ログイン</button>
  </form>
</body>
</html> --}}


{{-- @extends('layouts.guest')

@section('content')
<div class="container" style="max-width:420px;">
  <h3 class="mb-3">管理者ログイン</h3>
  <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">メールアドレス</label>
      <input type="email" name="email" class="form-control" required autofocus>
    </div>
    <div class="mb-3">
      <label class="form-label">パスワード</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-dark w-100">ログイン</button>
  </form>
</div>
@endsection --}}

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
