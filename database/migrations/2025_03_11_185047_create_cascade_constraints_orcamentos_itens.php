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
        
        Schema::table('orcamentos_itens', function (Blueprint $table) {
            $table->dropForeign(['orcamento_id']);
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->cascadeOnDelete();
            $table->dropForeign(['material_id']);
            $table->foreign('material_id')->references('id')->on('materiais')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
