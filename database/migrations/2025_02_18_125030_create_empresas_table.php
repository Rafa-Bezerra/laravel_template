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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fantasia');
            $table->enum('pessoa', ['cpf', 'cnpj']);
            $table->string('cpf')->nullable();            
            $table->string('cnpj')->nullable();
            $table->unsignedBigInteger('divisao_id');
            $table->foreign('divisao_id')->references('id')->on('divisoes');
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
