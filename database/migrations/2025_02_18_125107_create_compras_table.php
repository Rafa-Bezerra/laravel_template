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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orcamento_id')->nullable();
            $table->foreign('orcamento_id')->references('id')->on('orcamentos');
            $table->dateTime('data_compra', precision: 0);
            $table->dateTime('data_prazo', precision: 0)->nullable();
            $table->dateTime('data_entrega', precision: 0)->nullable();
            $table->decimal('valor_itens', total: 12, places: 2)->nullable();
            $table->decimal('valor_desconto', total: 12, places: 2)->nullable();
            $table->decimal('valor_total', total: 12, places: 2)->nullable();
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
