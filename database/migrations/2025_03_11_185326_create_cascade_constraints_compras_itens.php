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

        Schema::table('compras_itens', function (Blueprint $table) {
            $table->dropForeign(['compra_id']);
            $table->foreign('compra_id')->references('id')->on('compras')->cascadeOnDelete();
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
