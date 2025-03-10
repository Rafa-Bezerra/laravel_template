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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->enum('especie', ['compra', 'venda']);
            $table->unsignedBigInteger('orcamento_id')->nullable();
            $table->foreign('orcamento_id')->references('id')->on('orcamentos');  
            $table->unsignedBigInteger('compra_id')->nullable();
            $table->foreign('compra_id')->references('id')->on('compras');  
            $table->string('parcela');
            $table->dateTime('data', precision: 0);
            $table->decimal('valor', total: 12, places: 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
