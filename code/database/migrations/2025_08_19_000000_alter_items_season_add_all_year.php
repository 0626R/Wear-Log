<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // MySQL の ENUM 変更は DB::statement で
        DB::statement("ALTER TABLE items MODIFY season ENUM('春','夏','秋','冬','通年') NULL");
    }
    public function down(): void
    {
        DB::statement("ALTER TABLE items MODIFY season ENUM('春','夏','秋','冬') NULL");
    }
};
