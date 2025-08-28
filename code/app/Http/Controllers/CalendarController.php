<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutfitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // 表示する月（?month=YYYY-MM が来たらその月、無ければ今月）
        $base = $request->query('month');
        $month = $base ? Carbon::createFromFormat('Y-m', $base)->startOfMonth()
                       : Carbon::now()->startOfMonth();

        $start = $month->copy();
        $end   = $month->copy()->endOfMonth();

        $logs = OutfitLog::where('user_id', Auth::id())
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        return view('calendar.index', [
            'month' => $month,
            'prev'  => $month->copy()->subMonth()->format('Y-m'),
            'next'  => $month->copy()->addMonth()->format('Y-m'),
            'start' => $start,
            'end'   => $end,
            'logs'  => $logs,
        ]);
    }

    public function store(Request $request)
    {
        // 単体 or 複数どちらでも通るバリデーション（1回だけ）
    $data = $request->validate([
        'date'      => ['required','date'],
        'image'     => ['nullable','image','max:5120'], // 単体用
        'images'    => ['nullable','array'],            // 複数用
        'images.*'  => ['image','max:5120'],
    ]);

    // 送られてきたファイルを配列に正規化
    $files = [];
    if ($request->hasFile('images')) {
        $files = $request->file('images');          // 複数
    } elseif ($request->hasFile('image')) {
        $files = [$request->file('image')];         // 単体
    } else {
        return back()->withErrors(['image' => '画像ファイルを選択してください。']);
    }

    // すべて保存
    foreach ($files as $file) {
        $path = $file->store('outfits', 'public');
        OutfitLog::create([
            'user_id'    => Auth::id(),
            'date'       => $data['date'],
            'image_path' => $path,
        ]);
    }


        // 月を保ったまま戻る
        $month = Carbon::parse($request->input('date'))->format('Y-m');
        return redirect()->route('calendar', ['month' => $month])->with('ok', '保存しました');    }
}
