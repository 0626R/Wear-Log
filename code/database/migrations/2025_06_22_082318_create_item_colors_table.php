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
        Schema::create('item_colors', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // items.id 外部キー
            $table->foreignId('color_id')->constrained()->onDelete('cascade'); // colors.id 外部キー
            $table->primary(['item_id', 'color_id']); // 複合主キー
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_colors');
    }
};
