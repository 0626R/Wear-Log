<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // 一般ユーザー
       User::updateOrCreate(
        ['email' => 'test@example.com'],
        [
            'last_name'       => '山田',
            'first_name'      => '太郎',
            'last_name_kana'  => 'ヤマダ',
            'first_name_kana' => 'タロウ',
            'is_premium'      => false,
            'password'        => Hash::make('User1234!'),
        ]
    );

    // プレミアムユーザー
    User::updateOrCreate(
        ['email' => 'premium@example.com'],
        [
            'last_name'       => '佐藤',
            'first_name'      => '花子',
            'last_name_kana'  => 'サトウ',
            'first_name_kana' => 'ハナコ',
            'is_premium'      => true,
            'password'        => Hash::make('Premium1234!'),
        ]
    );
    }
}

