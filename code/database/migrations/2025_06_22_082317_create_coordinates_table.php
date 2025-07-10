<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coordinates', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // usersテーブルとの外部キー
            $table->date('date'); // 日付
            $table->integer('temperature_min'); // 最低気温
            $table->integer('temperature_max'); // 最高気温
            $table->string('weather', 20)->nullable(); // 天気（NULL許可）
            $table->double('latitude')->nullable(); // 緯度
            $table->double('longitude')->nullable(); // 経度
            $table->timestamps(); // created_at, updated_at（自動でCURRENT_TIMESTAMP）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordinates');
    }
};
