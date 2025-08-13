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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 外部キー
            $table->string('brand', 50)->nullable();
            $table->enum('season', ['春', '夏', '秋', '冬'])->nullable();
            $table->integer('price')->nullable();
            $table->date('purchased_at')->nullable();
            $table->enum('status', ['出品しない', '出品する', '検討中']);
            $table->text('memo')->nullable();
            $table->integer('wear_count')->default(0);
            $table->string('image', 100)->nullable();
            $table->boolean('deleted_flg')->default(0); // 0=有効、1=削除
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
