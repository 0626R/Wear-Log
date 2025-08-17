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
                    {{-- @if(session('selected_category'))
                        <p class="mt-2">
                            選択中: {{ implode(', ', session('selected_category')) }}
                        </p>
                    @endif --}}
                    @php
                        $selectedCatIds = old('categories', data_get(session('item_form'), 'categories', session('selected_categories', [])));
                        $selectedCats   = $selectedCatIds ? \App\Models\Category::whereIn('id',$selectedCatIds)->get() : collect();
                    @endphp

                    @if($selectedCats->isNotEmpty())
                    <p class="mt-2">選択中:
                        @foreach($selectedCats as $cat)
                        <span class="badge bg-secondary me-1">{{ $cat->name }}</span>
                        <input type="hidden" name="categories[]" value="{{ $cat->id }}">
                        @endforeach
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

                {{-- 着用回数（wear_count） --}}
                <div class="mb-3">
                    <label>着用回数</label>
                    <input type="number" name="wear_count" class="form-control"
                        min="0" step="1"
                        value="{{ old('wear_count', data_get(session('item_form'), 'wear_count', 0)) }}">
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

    <script>
        // カメラアイコンを差し替えて写真にする
        document.addEventListener('DOMContentLoaded', () => {
          const input   = document.getElementById('imageInput');
          const preview = document.getElementById('preview');
      
          if (!input || !preview) return; 
      
          input.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
      
            preview.src = URL.createObjectURL(file);
          });
        });
      </script>

    {{-- フッターに被らないためのスペース --}}
    <div style="height: 96px;"></div>
</div>

@endsection

<script>


    // サーバ側 flash を Blade で埋め込み
    const FROM_SELECTOR = {{ session('from_selector') ? 'true' : 'false' }};

    // 普通に来た時は下書きを消す
    if (!FROM_SELECTOR) {
    localStorage.removeItem('item_draft');
    }
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