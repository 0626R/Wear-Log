<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with(['colors','categories'])
            ->where('user_id', auth()->id())
            ->latest('id')
            ->paginate(30);

        return view('statistics.index', compact('items'));
    }

    public function exportPdf(Request $request)
{
    $items = Item::where('user_id', auth()->id())
        ->with(['colors','categories'])
        ->orderBy('brand')
        ->get();

        $pdf = Pdf::setOptions([
            'defaultFont'     => 'NotoSansJP',
            'isRemoteEnabled' => true,
            'chroot'          => base_path(),
        ])->loadView('statistics.pdf', compact('items')); 

    return $pdf->download('wearlog-checklist.pdf');
}
}
