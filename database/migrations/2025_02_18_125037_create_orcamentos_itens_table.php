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
        Schema::create('orcamentos_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orcamento_id');
            $table->foreign('orcamento_id')->references('id')->on('orcamentos');    
            $table->dateTime('data', precision: 0);
            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')->references('id')->on('materiais');    
            $table->decimal('quantidade', total: 12, places: 2);   
            $table->decimal('preco_unitario', total: 12, places: 2);   
            $table->decimal('valor_desconto', total: 12, places: 2);   
            $table->decimal('valor_total', total: 12, places: 2);   
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos_itens');
    }
};
