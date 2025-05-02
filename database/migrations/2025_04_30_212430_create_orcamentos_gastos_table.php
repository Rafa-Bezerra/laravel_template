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
        Schema::create('orcamentos_gastos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orcamento_id');
            $table->foreign('orcamento_id')->references('id')->on('orcamentos');  
            $table->decimal('valor', total: 12, places: 2); 
            $table->enum('controle', ['pendente', 'pago'])->nullable();
            $table->enum('especie', ['pessoas', 'material'])->nullable();
            $table->unsignedBigInteger('banco_id')->nullable();
            $table->foreign('banco_id')->references('id')->on('bancos');
            $table->enum('tipo_pagamento', ['boleto','dinheiro','pix','crédito','débito','depósito','transferência'])->nullable();
            $table->string('observacao')->nullable();
            $table->dateTime('data', precision: 0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos_gastos');
    }
};
