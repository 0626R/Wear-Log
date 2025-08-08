@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="text-center mb-2">アイテム登録</h2>
    <hr class="mb-4" style="width: 100%; height: 2px; background-color: black; margin: 0 auto;">

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
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
                    <a href="{{ route('items.selectCategory') }}" class="btn btn-outline-secondary">カテゴリを選択</a>
                    @if(session('selected_category'))
                        <p class="mt-2">選択中: {{ implode('・', session('selected_category')) }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label>ブランド</label>
                    <input type="text" name="brand" class="form-control">
                </div>
                {{-- カラー：画面遷移で選択 --}}
                <div class="mb-3">
                    <label>カラー</label><br>
                    <input type="text" name="color" class="form-control" value="{{ request('color') }}" readonly>
                    <a href="{{ route('items.selectColor') }}" class="text-decoration-none small text-primary">カラーを選択</a>
                    @if(session('selected_colors'))
                    <p>選択されたカラー:
                        @foreach(App\Models\Color::find(session('selected_colors')) as $color)
                            <span class="badge bg-secondary">{{ $color->name }}</span>
                        @endforeach
                    </p>
                    @endif

                </div>
                
                <div class="mb-3">
                    <label>購入価格</label>
                    <input type="number" name="price" class="form-control">
                </div>
                {{-- シーズン：ドロップダウンで選択 --}}
                <div class="mb-3">
                    <label>シーズン</label>
                    <select name="season" class="form-control">
                        <option value="春">春</option>
                        <option value="夏">夏</option>
                        <option value="秋">秋</option>
                        <option value="冬">冬</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>購入日</label>
                    <input type="date" name="purchase_date" class="form-control">
                </div>
                <div class="mb-3">
                    <label>出品状況</label>
                    <select name="status" class="form-control">
                        <option value="出品する">出品する</option>
                        <option value="しない">しない</option>
                        <option value="検討中">検討中</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>メモ</label>
                    <textarea name="memo" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-dark w-50">完了</button>
            </div>
        </div>
    </form>
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
