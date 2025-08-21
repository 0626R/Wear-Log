<x-guest-layout>
    <style>
        .register-card{max-width:720px;margin:40px auto;padding:28px;border-radius:12px;background:#fff;
          box-shadow:0 8px 24px rgba(0,0,0,.08)}
        .register-title{font-size:28px;font-weight:800;letter-spacing:.04em;margin-bottom:24px}
        .grid{display:grid;gap:14px}
        @media(min-width:640px){ .grid-2{grid-template-columns:1fr 1fr} }
        .form-label{display:block;font-weight:600;margin:8px 0 6px}
        .form-control{width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;font-size:16px}
        .btn-primary{display:block;width:100%;padding:12px 16px;margin-top:22px;border-radius:10px;
          background:#111;color:#fff;font-weight:700;text-align:center}
        .helper{margin-top:16px;font-size:14px;color:#6b7280}
        .error{color:#b91c1c;font-size:13px;margin-top:4px}
      </style>
  
    <form method="POST" action="{{ route('register') }}">
      @csrf
  
      <div class="register-card">
        <h1 class="register-title">新規会員登録</h1>
  
        {{-- エラー表示（必要な場合） --}}
        @if ($errors->any())
          <div style="background:#fff3f3;border:1px solid #fca5a5;color:#b91c1c;border-radius:8px;padding:10px 12px;margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
              @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif
  
          {{-- 姓／名 --}}
      <div class="grid grid-2">
        <div>
          <label class="form-label" for="last_name">姓</label>
          <input id="last_name" name="last_name" type="text" class="form-control"
                 value="{{ old('last_name') }}" required>
          @error('last_name')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="form-label" for="first_name">名</label>
          <input id="first_name" name="first_name" type="text" class="form-control"
                 value="{{ old('first_name') }}" required>
          @error('first_name')<div class="error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- （任意）カナ --}}
      <div class="grid grid-2">
        <div>
          <label class="form-label" for="last_name_kana">姓（カナ）</label>
          <input id="last_name_kana" name="last_name_kana" type="text" class="form-control"
                 value="{{ old('last_name_kana') }}" pattern="^[ァ-ヶー　\s]*$" placeholder="ヤマダ">
          @error('last_name_kana')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="form-label" for="first_name_kana">名（カナ）</label>
          <input id="first_name_kana" name="first_name_kana" type="text" class="form-control"
                 value="{{ old('first_name_kana') }}" pattern="^[ァ-ヶー　\s]*$" placeholder="タロウ">
          @error('first_name_kana')<div class="error">{{ $message }}</div>@enderror
        </div>
      </div>

        {{-- メールアドレス --}}
        <label class="form-label" for="email">メールアドレス</label>
        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
  
        {{-- パスワード --}}
        <label class="form-label" for="password">ご希望のパスワード</label>
        <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password">
  
        {{-- パスワード（確認） --}}
        <label class="form-label" for="password_confirmation">ご希望のパスワード（確認用）</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
  
        {{-- 名前（表示名）
        <label class="form-label" for="name">お名前</label>
        <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
   --}}
        <button class="btn-primary" type="submit">会員情報を登録する</button>
  
        <div class="helper">
          すでにアカウントをお持ちの方は
          <a href="{{ route('login') }}">こちらからログイン</a>
        </div>
      </div>
    </form>
  </x-guest-layout>
  