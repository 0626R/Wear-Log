@php
  $isEdit = isset($item);
@endphp

<div class="row">
  <div class="col-md-4 text-center">
    <label for="imageInput" style="cursor:pointer;display:inline-block">
      <img id="preview"
           src="{{ ($isEdit && $item->image) ? asset('storage/'.$item->image) : asset('images/icon_camera.png') }}"
           alt="画像" style="width:100%;max-width:180px;height:auto;border:1px solid #ddd;padding:10px;border-radius:12px;object-fit:cover;aspect-ratio:1/1;">
      <p class="mt-2">画像を選択</p>
    </label>
    <input type="file" id="imageInput" name="image" accept="image/*" style="display:none">
  </div>

  <div class="col-md-8">
    {{-- 例：ブランド --}}
    <div class="mb-3">
      <label class="form-label">ブランド</label>
      <input type="text" name="brand" class="form-control"
             value="{{ old('brand', $isEdit ? $item->brand : data_get(session('item_form'), 'brand','')) }}">
    </div>

    {{-- 例：カラー（チェックボックス） --}}
    <div class="mb-3">
      <label class="form-label">カラー</label>
      <div class="d-flex flex-wrap gap-3">
        @foreach($colors as $color)
          @php
            $checked = in_array($color->id, old('colors', $selectedColors ?? session('selected_colors', [])));
          @endphp
          <label class="form-check-label">
            <input type="checkbox" name="colors[]" value="{{ $color->id }}" class="form-check-input" {{ $checked?'checked':'' }}>
            {{ $color->name }}
          </label>
        @endforeach
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">購入価格</label>
      <input type="number" name="price" class="form-control"
             value="{{ old('price', $isEdit ? $item->price : data_get(session('item_form'),'price','')) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">シーズン</label>
      @php $current = old('season', $isEdit ? $item->season : data_get(session('item_form'),'season','')); @endphp
      <select name="season" class="form-control">
        @foreach(['春','夏','秋','冬','通年'] as $s)
          <option value="{{ $s }}" {{ $current===$s?'selected':'' }}>{{ $s }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">購入日</label>
      <input type="date" name="purchased_at" class="form-control"
             value="{{ old('purchased_at', $isEdit ? optional($item->purchased_at)->format('Y-m-d') : data_get(session('item_form'),'purchased_at','')) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">出品状況</label>
      @php $st = old('status', $isEdit ? $item->status : data_get(session('item_form'),'status','出品しない')); @endphp
      <select name="status" class="form-control">
        @foreach(['出品しない','出品する','検討中'] as $opt)
          <option value="{{ $opt }}" {{ $opt===$st?'selected':'' }}>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">メモ</label>
      <textarea name="memo" class="form-control" rows="3">{{ old('memo', $isEdit ? $item->memo : data_get(session('item_form'),'memo','')) }}</textarea>
    </div>
  </div>
</div>

<script>
const input = document.getElementById('imageInput');
const preview = document.getElementById('preview');
input.addEventListener('change', () => {
  const f = input.files?.[0];
  if (!f) return;
  const url = URL.createObjectURL(f);
  preview.src = url; // カメラアイコン即置換
});
</script>
