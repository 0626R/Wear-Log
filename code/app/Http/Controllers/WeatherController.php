<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $lat = (float) $request->query('lat');
        $lon = (float) $request->query('lon');

        if (!$lat || !$lon) {
            return response()->json(['error' => 'lat/lon required'], 400);
        }

        // Open-Meteo: 今日/明日の最高/最低・天気コード、現在の天気
        $forecast = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude'           => $lat,
            'longitude'          => $lon,
            'current_weather'    => true,
            'timezone'           => 'auto',
            'daily'              => 'weathercode,temperature_2m_max,temperature_2m_min',
        ])->json();

        // 地名取得（市区町村）
        $place = Http::get('https://geocoding-api.open-meteo.com/v1/reverse', [
            'latitude'  => $lat,
            'longitude' => $lon,
            'language'  => 'ja',
            'format'    => 'json',
        ])->json();

        $name = data_get($place, 'results.0.name');
        $admin1 = data_get($place, 'results.0.admin1'); // 県名
        $city = $name ? $name : sprintf('%.3f, %.3f', $lat, $lon);
        $locationLabel = $admin1 ? "{$city}" : $city;

        $dailyDates = data_get($forecast, 'daily.time', []);
        $codes      = data_get($forecast, 'daily.weathercode', []);
        $tmax       = data_get($forecast, 'daily.temperature_2m_max', []);
        $tmin       = data_get($forecast, 'daily.temperature_2m_min', []);

        // アイコン/テキスト
        $map = [
            0 => ['☀️','快晴'],
            1 => ['🌤','晴れ'],
            2 => ['⛅️','一部曇り'],
            3 => ['☁️','曇り'],
            45 => ['🌫','霧'],
            48 => ['🌫','霧'],
            51 => ['🌦','霧雨(弱)'], 53 => ['🌦','霧雨'], 55 => ['🌧','霧雨(強)'],
            61 => ['🌦','雨(弱)'],   63 => ['🌧','雨'],     65 => ['🌧','雨(強)'],
            66 => ['🌧','凍雨(弱)'],  67 => ['🌧','凍雨(強)'],
            71 => ['🌨','雪(弱)'],   73 => ['🌨','雪'],     75 => ['❄️','大雪'],
            77 => ['❄️','雪あられ'],
            80 => ['🌦','にわか雨(弱)'], 81 => ['🌧','にわか雨'], 82 => ['🌧','にわか雨(強)'],
            85 => ['🌨','にわか雪(弱)'], 86 => ['❄️','にわか雪(強)'],
            95 => ['⛈','雷雨'], 96 => ['⛈','雷雨(ひょう)'], 99 => ['⛈','激しい雷雨'],
        ];
        $w = fn($code) => $map[$code] ?? ['❔','-'];

        $today = [
            'date'    => $dailyDates[0] ?? null,
            'wday'    => $this->weekday($dailyDates[0] ?? null),
            'tmax'    => isset($tmax[0]) ? round($tmax[0]) : null,
            'tmin'    => isset($tmin[0]) ? round($tmin[0]) : null,
            'code'    => $codes[0] ?? null,
            'icon'    => $w($codes[0] ?? null)[0],
            'desc'    => $w($codes[0] ?? null)[1],
        ];

        $tomorrow = [
            'date'    => $dailyDates[1] ?? null,
            'wday'    => $this->weekday($dailyDates[1] ?? null),
            'tmax'    => isset($tmax[1]) ? round($tmax[1]) : null,
            'tmin'    => isset($tmin[1]) ? round($tmin[1]) : null,
            'code'    => $codes[1] ?? null,
            'icon'    => $w($codes[1] ?? null)[0],
            'desc'    => $w($codes[1] ?? null)[1],
        ];

        return response()->json([
            'location' => $locationLabel,
            'today'    => $today,
            'tomorrow' => $tomorrow,
        ]);

        
    }

    private function weekday(?string $isoDate): ?string
    {
        if (!$isoDate) return null;
        $w = ['日','月','火','水','木','金','土'];
        $ts = strtotime($isoDate);
        return $w[date('w', $ts)];
    }
}
