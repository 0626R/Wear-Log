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
        Schema::create('item_categories', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // items.id 外部キー
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // categories.id 外部キー
            $table->primary(['item_id', 'category_id']); // 複合主キー
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_categories');
    }
};
