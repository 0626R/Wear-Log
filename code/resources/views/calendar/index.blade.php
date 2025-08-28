@extends('layouts.wearlog')

@section('content')
<div class="container mt-3">

  {{-- ヘッダ：前月/翌月 --}}
  <div class="d-flex align-items-center justify-content-between mb-3">
    <a class="btn btn-outline-secondary" href="{{ route('calendar', ['month'=>$prev]) }}">‹</a>
    <h4 class="m-0">{{ $month->format('Y年n月') }}</h4>
    <a class="btn btn-outline-secondary" href="{{ route('calendar', ['month'=>$next]) }}">›</a>
  </div>

  @php
    $dow = ['日','月','火','水','木','金','土'];
    $cursor = $start->copy();
  @endphp

  <style>
    .day-row{border:1px solid #eee;border-radius:10px;margin-bottom:12px;background:#fff;}
    .day-head{display:flex;justify-content:space-between;align-items:center;
              padding:10px 14px;background:#f7f7f7;border-bottom:1px solid #eee;}
    .day-body{padding:16px;display:flex;justify-content:center;align-items:center;min-height:120px;}
    .day-plus{font-size:42px;color:#888;}
    .thumb{max-width:100%;max-height:240px;border-radius:10px;box-shadow:0 1px 6px rgba(0,0,0,.08);}
    .pen{background:none;border:0;font-size:18px;color:#666;}
    input[type=file]{display:none;}
  </style>

  @for ($d = $start->copy(); $d->lte($end); $d->addDay())
    @php
      $key = $d->toDateString(); // Y-m-d
      $imgs = $logs->get($key) ?? collect();   // ← 複数件
    @endphp
    <div class="day-row">
      <div class="day-head">
        <div>{{ $d->format('n月j日') }}（{{ $dow[$d->dayOfWeek] }}）</div>
        <div>
          <form method="POST" action="{{ route('calendar.upload') }}" enctype="multipart/form-data" id="f-{{ $key }}">
            @csrf
            <input type="hidden" name="date" value="{{ $key }}">
            <input type="file" name="images[]" id="file-{{ $key }}" accept="image/*" multiple>
            <button type="button" class="pen" onclick="document.getElementById('file-{{ $key }}').click()">
              ✏️
            </button>
          </form>
        </div>
      </div>
      <div class="day-body">
        @if($imgs->isNotEmpty())
          <div class="thumbs">
            @foreach($imgs as $log)
              <img class="thumb" src="{{ asset('storage/'.$log->image_path) }}" alt="outfit">
            @endforeach
          </div>
        @else
          <div class="day-plus">＋</div>
        @endif
      </div>
    </div>
  @endfor

  <div style="height:80px"></div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('change', (e) => {
  if (e.target.matches('input[type=file][id^="file-"]') && e.target.files.length) {
    e.target.closest('form').submit();
  }
}, false);
</script>
@endpush


