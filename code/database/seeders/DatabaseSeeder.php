<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ColorSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // テストユーザーを1人作成（姓・名構成に対応）
        User::factory()->create([
            'last_name' => '山田',
            'first_name' => '太郎',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'is_premium' => false,
        ]);

        // カラー、カテゴリシーダーを呼び出す 
        $this->call([
            CategorySeeder::class,
            ColorSeeder::class,
        ]);
    }
    
}
