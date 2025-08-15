@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-5" style="padding-bottom: 96px;">

    <h2 class="text-center mb-2">アイテム登録</h2>
    <hr class="mb-4" style="width: 100%; height: 2px; background-color: black; margin: 0 auto;">

    <form id="itemForm" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{-- 左側：画像登録 --}}
            <div class="col-md-4 text-center">
                <label for="imageInput" style="cursor: pointer;">
                    <img id="preview" src="{{ asset('images/icon_camera.png') }}" alt="画像登録" style="width:100%; max-width:150px; height:auto; border:1px solid #ccc; padding: 10px;">
                    <p class="mt-2">画像を選択</p>
                </label>
                <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
            </div>

            {{-- 右側：入力欄 --}}
            <div class="col-md-8">
                {{-- カテゴリ：画面遷移で選択 --}}
                <div class="mb-3">
                    <label>カテゴリ</label><br>
                    <a href="{{ route('items.selectCategory') }}" class="btn btn-outline-secondary" onclick="event.preventDefault(); goAfterSaveDraft(this.href);">カテゴリを選択</a>
                    @if(session('selected_category'))
                        <p class="mt-2">
                            選択中: {{ implode(', ', session('selected_category')) }}
                        </p>
                    @endif
                </div>
                <div class="mb-3">
                    <label>ブランド</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', data_get(session('item_form'), 'brand', '')) }}">
                </div>
                {{-- カラー：画面遷移で選択 --}}
                <div class="mb-3">
                    <label>カラー</label><br>
                    {{-- <input type="text" name="color" class="form-control" value="{{ request('color') }}" readonly> --}}
                    <a href="{{ route('items.selectColor') }}" class="btn btn-outline-secondary" onclick="event.preventDefault(); goAfterSaveDraft(this.href);">カラーを選択</a>
                    
                    @php
                        $selectedIds = session('selected_colors', []);
                        $selectedColors = $selectedIds ? \App\Models\Color::whereIn('id', $selectedIds)->get() : collect();
                    @endphp
                    
                    @if($selectedColors->isNotEmpty())
                    <p>選択されたカラー:
                        @foreach($selectedColors as $c)
                        <span class="badge bg-secondary me-1">{{ $c->name }}</span>
                        {{-- 保存用 hidden（最終submit時に一緒に送る） --}}
                        <input type="hidden" name="colors[]" value="{{ $c->id }}">
                        @endforeach
                    </p>
                    @endif
                </div>
                
                <div class="mb-3">
                    <label>購入価格</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', data_get(session('item_form'), 'price', '')) }}">
                {{-- シーズン：ドロップダウンで選択 --}}
                    <div class="mb-3">
                        <label>シーズン</label>
                        <select name="season" class="form-control">
                            @foreach(['春','夏','秋','冬', '通年'] as $s)
                                <option value="{{ $s }}" @selected(old('season', data_get(session('item_form'), 'season')) === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                </div>
                
                <div class="mb-3">
                    <label>購入日</label>
                    <input type="date" name="purchased_at" class="form-control" value="{{ old('purchased_at', data_get(session('item_form'), 'purchased_at', '')) }}">
                </div>
                <div class="mb-3">
                    <label>出品状況</label>
                    <select name="status" class="form-control">
                        @foreach(['出品しない','出品する','検討中'] as $st)
                            <option value="{{ $st }}" @selected(old('status', data_get(session('item_form'), 'status')) === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>メモ</label>
                    <textarea name="memo" class="form-control" rows="2">{{ old('memo', data_get(session('item_form'), 'memo', '')) }}</textarea>
                </div>
            </div>
            </div>
            <div class="col-12">
                <div class="text-center mt-4">
                <button type="submit" class="btn btn-dark w-50">完了</button>
            </div>
        </div>
    </form>

    {{-- フッターに被らないためのスペース --}}
    <div style="height: 96px;"></div>
</div>

{{-- @section('scripts')
<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('imageInput').click();

        // 画像プレビュー表示
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('preview');

        input.addEventListener('change', () => {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    });
</script>
@endsection --}}


@endsection

<script>
    document.getElementById('imageInput').addEventListener('change', (e) => {
        const [file] = e.target.files;
        if (!file) return;
        const url = URL.createObjectURL(file);
        document.getElementById('preview').src = url;
        });


    // 保存するフィールド名を列挙
    const FIELDS = ['brand', 'price', 'season', 'purchased_at', 'status', 'memo'];
  
    // 入力値を localStorage へ
    function saveDraft() {
      const obj = {};
      FIELDS.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) obj[name] = el.value ?? '';
      });
      localStorage.setItem('item_draft', JSON.stringify(obj));
    }
  
    // localStorage から復元
    function restoreDraft() {
      const raw = localStorage.getItem('item_draft');
      if (!raw) return;
      try {
        const obj = JSON.parse(raw);
        FIELDS.forEach(name => {
          const el = document.querySelector(`[name="${name}"]`);
          if (el && obj[name] != null) el.value = obj[name];
        });
      } catch (e) {}
    }
  
    // 入力のたびに自動保存
    document.addEventListener('input', (e) => {
      if (FIELDS.includes(e.target.name)) saveDraft();
    });
  
    // 初期表示で復元
    document.addEventListener('DOMContentLoaded', restoreDraft);
  
    // フォーム送信時に下書きを消す（送信後は不要なので）
    document.getElementById('itemForm').addEventListener('submit', () => {
      localStorage.removeItem('item_draft');
    });
  </script>

<script>
    async function goAfterSaveDraft(url){
      const form = document.getElementById('itemForm');
      const fd   = new FormData(form);
      await fetch('{{ route('items.saveDraft') }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: fd
      });
      location.href = url;
    }
    
    document.getElementById('btnCategory')
      .addEventListener('click', () => goAfterSaveDraft('{{ route('items.selectCategory') }}'));
    
    document.getElementById('btnColor')
      .addEventListener('click', () => goAfterSaveDraft('{{ route('items.selectColor') }}'));


      
    </script>