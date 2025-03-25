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
        DB::statement("ALTER TABLE pagamentos MODIFY COLUMN especie ENUM('compra', 'venda', 'despesa')");
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->string('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE pagamentos MODIFY COLUMN especie ENUM('compra', 'venda')");
        
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn(['name']);
        });
    }
};
