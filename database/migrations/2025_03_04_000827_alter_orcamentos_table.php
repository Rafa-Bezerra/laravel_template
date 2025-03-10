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
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->decimal('valor_servicos', total: 12, places: 2)->nullable();
            $table->decimal('valor_orcamento', total: 12, places: 2)->nullable();
            $table->decimal('valor_saldo', total: 12, places: 2)->nullable();
            $table->decimal('valor_impostos', total: 12, places: 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn(['valor_servicos', 'valor_orcamento', 'valor_saldo', 'valor_impostos']);
        });
    }
};
