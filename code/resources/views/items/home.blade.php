@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- ヘッダー（ロゴ＋通知アイコンなど） --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- 左の空要素（スペース確保） --}}
        <div style="width: 40px;"></div>

       {{-- ロゴ画像 --}}
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo1.png') }}" alt="Wear Log ロゴ" style="height: 80px;">
        </div>

        {{-- 右のアイコン --}}
        <div>
            <a href="{{ route('items.filter') }}">
                <img src="{{ asset('images/filter_icon.png') }}" alt="フィルター" style="height: 50px;">
            </a>
        </div>
    </div>

    {{-- 天気情報（OpenWeatherMap） --}}
    <div id="weatherBar" class="weather-bar bg-light rounded px-3 py-3 mb-3">
        <span id="wb-today"></span>
        <span id="wb-sep">／</span>
        <span id="wb-tomorrow"></span>
        <span id="wb-city" class="city-badge"></span>
    </div>
    
    <style>
        .weather-bar{ display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
        .weather-bar img{ vertical-align:-4px; width:20px; height:20px; }
        .city-badge{
        margin-left:auto; background:#19764b; color:#fff;
        padding:4px 10px; border-radius:8px; font-weight:600;
        }
    </style>

  
    {{-- カテゴリ --}}
    @php
        $categories = ['すべて','トップス','ボトムス','ワンピース','アウター','セットアップ','バッグ','シューズ','アクセサリー','ファッション雑貨','その他'];
        // ↓すべて」を初期値
        $selectedCategory = request()->input('category', 'すべて');
    @endphp

    {{-- カテゴリ選択で下線表示 --}}
    <div class="d-flex overflow-auto border-bottom mb-3 px-3">
        @foreach($categories as $category)
            <a href="{{ route('items.home', ['category' => $category]) }}"
            class="me-4 pb-2 text-decoration-none
            {{ $selectedCategory === $category 
                    ? 'border-bottom border-3 border-primary text-primary fw-bold' 
                    : 'text-secondary border-bottom border-0' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>


    {{-- 商品カード --}}
    <style>
        .item-grid{
          display:grid;
          grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); /* PCで7〜8枚並ぶ */
          gap:12px;
        }
        .item-card{
          width:100%;
          aspect-ratio:1/1;          /* 正方形 */
          overflow:hidden;
          border:1px solid #e5e5e5;
          border-radius:10px;
          background:#fff;
          box-shadow:0 1px 4px rgba(0,0,0,.04);
        }
        .item-card img{
          width:100%;
          height:100%;
          object-fit:cover;          /* はみ出しをトリミング */
          display:block;
        }
        .card{
          border-radius:12px;
          overflow:hidden;
          background:#fff;
          box-shadow:0 1px 6px rgba(0,0,0,.06)
        }
        .thumb{
            width:100%;
            aspect-ratio:1/1;object-fit:cover;
            display:block
        }
        
        @media (max-width:576px){ .item-grid{ grid-template-columns:repeat(3,1fr); } }
        @media (min-width:577px) and (max-width:992px){ .item-grid{ grid-template-columns:repeat(5,1fr); } }
        .weather-bar{display:flex; align-items:center; gap:18px; flex-wrap:wrap; font-size:18px; line-height:1.3; }
        .weather-bar strong{ font-size:19px; }
        .weather-bar img:not(.owm-icon){ vertical-align:-4px; width:30px; height:30px; }
        .owm-icon{ width:40px; height:40px; vertical-align:-6px; filter: drop-shadow(0 0 1px rgba(0,0,0,.25)); }
        .temp-max{ color:#d33; font-weight:600; }
        .temp-min{ color:#1b9aaa; font-weight:600; }
        .city-badge{
            margin-left:auto; background:#19764b; color:#fff;
            padding:6px 12px; border-radius:10px; font-weight:700; font-size:16px;
        }
        .owm-icon{ filter: drop-shadow(0 0 1px rgba(0,0,0,.25)); } 
        .city-badge{margin-left:auto;background:#19764b;color:#fff;padding:4px 12px;border-radius:10px;font-weight:600}
      </style>
      
      <div class="item-grid">
        @foreach ($items as $item)
          <a class="card block" href="{{ route('items.show', $item) }}">
            <img src="{{ $item->image ? asset('storage/'.$item->image) : asset('images/no_image.png') }}"
                alt="{{ $item->brand }}" class="thumb"/>
          </a>
          {{-- <div class="item-card">
            @if ($item->image)
              <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->brand }}">
            @else
              <img src="{{ asset('images/no_image.png') }}" alt="no image">
            @endif
          </div> --}}
        @endforeach
      </div>
    {{-- フッターに被らないためのスペース --}}
    <div style="height: 96px;"></div>


</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
  const API_KEY = "{{ config('services.openweather.key') }}";
  if (!API_KEY) { console.error('OpenWeather API key is empty'); return; }

  const $today    = document.getElementById('wb-today');
  const $tomorrow = document.getElementById('wb-tomorrow');
  const $city     = document.getElementById('wb-city');
  if (!$today || !$tomorrow || !$city) return;

  // 表示用ユーティリティ
  const fmtDate = (ts, tz=0) => {
    const d = new Date((ts+tz)*1000);
    const s = d.toLocaleDateString('ja-JP',{month:'numeric',day:'numeric',weekday:'short'});
    return s.replace('/', '月') + '日';
  };
  const iconTag = (code, alt='') =>
    `<img class="owm-icon" src="https://openweathermap.org/img/wn/${code}@4x.png" alt="${alt}">`;

  // 3時間刻み予報から、日毎に要約（最高/最低と、正午に近いアイコン）
  function summarize(list, tz){
    const byDay = {};
    for (const it of list){
      const key = new Date((it.dt+tz)*1000).toISOString().slice(0,10);
      (byDay[key] ??= []).push(it);
    }
    const keys = Object.keys(byDay).sort();
    const pick = k => {
      const a = byDay[k];
      const tmin = Math.min(...a.map(x=>x.main.temp_min));
      const tmax = Math.max(...a.map(x=>x.main.temp_max));
      const mid = a.reduce((best, x) => {
        const h = new Date((x.dt+tz)*1000).getHours();
        const hb= new Date((best.dt+tz)*1000).getHours();
        return Math.abs(h-12) < Math.abs(hb-12) ? x : best;
      }, a[0]);
      return { tmin, tmax, icon: mid.weather[0].icon, desc: mid.weather[0].description, dt: a[0].dt };
    };
    return { today: keys[0] && pick(keys[0]), tomorrow: keys[1] && pick(keys[1]) };
  }

  // 逆ジオの結果から「市/町/村」を優先し、必要なら“名取”を最優先
  function pickCityName(rs){
    if (!Array.isArray(rs) || !rs.length) return '現在地';
    const preferNatori = rs.find(r =>
      (r.local_names?.ja && r.local_names.ja.includes('名取')) || (r.name && r.name.includes('名取'))
    );
    if (preferNatori) return preferNatori.local_names?.ja || preferNatori.name;

    const prefType = rs.find(r => ['city','town','village'].includes(r.type));
    const cand = prefType || rs[0];
    return cand.local_names?.ja || cand.name || '現在地';
  }

  async function fetchWeather(lat, lon){
    try{
      // 現在天気（タイムゾーン用） & 3時間予報
      const u1 = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&lang=ja&appid=${API_KEY}`;
      const u2 = `https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&units=metric&lang=ja&appid=${API_KEY}`;
      const [r1, r2] = await Promise.all([fetch(u1), fetch(u2)]);
      if (!r1.ok || !r2.ok){
        console.error('OWM HTTP', r1.status, r2.status, await r1.text(), await r2.text());
        return;
      }
      const [curr, fc] = await Promise.all([r1.json(), r2.json()]);
      const tz = curr.timezone ?? 0;

      const { today, tomorrow } = summarize(fc.list, tz);
      if (today){
        $today.innerHTML =
          `<strong>${fmtDate(today.dt,tz)}</strong> ${iconTag(today.icon,today.desc)}
           <span class="temp-max">${Math.round(today.tmax)}°C</span> /
           <span class="temp-min">${Math.round(today.tmin)}°C</span>`;
      }
      if (tomorrow){
        $tomorrow.innerHTML =
          `<strong>${fmtDate(tomorrow.dt,tz)}</strong> ${iconTag(tomorrow.icon,tomorrow.desc)}
           <span class="temp-min">${Math.round(tomorrow.tmax)}°C</span> /
           <span class="temp-min">${Math.round(tomorrow.tmin)}°C</span>`;
      }

      // 逆ジオで市名を優先的に表示
      const geo = await fetch(
        `https://api.openweathermap.org/geo/1.0/reverse?lat=${lat}&lon=${lon}&limit=5&appid=${API_KEY}`
      );
      if (geo.ok){
        const rs = await geo.json();
        $city.textContent = pickCityName(rs);
      } else {
        $city.textContent = curr.name || '現在地';
      }
    }catch(e){
      console.error(e);
    }
  }

  const fallback = () => fetchWeather(35.681236, 139.767125); // 東京駅
  if (navigator.geolocation){
    navigator.geolocation.getCurrentPosition(
      p => fetchWeather(p.coords.latitude, p.coords.longitude),
      () => fallback(),
      { enableHighAccuracy:true, timeout:6000 }
    );
  } else {
    fallback();
  }
});
    </script>