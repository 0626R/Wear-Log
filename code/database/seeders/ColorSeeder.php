<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = ['ホワイト', 'ブラック', 'グレー', 'ブラウン', 'ベージュ', 'キャメル', 'グリーン', 'カーキ', 'ネイビー', 'ブルー', 'イエロー', 'ピンク', 'レッド', 'パープル', 'ボルドー', 'オレンジ','シルバー', 'ゴールド', 'その他',];

        foreach ($colors as $color) {
            Color::create(['name' => $color]);
        }
    }
}
