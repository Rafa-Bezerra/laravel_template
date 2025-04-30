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
        Schema::table('empresas_enderecos', function (Blueprint $table) {
            $table->string('cep')->nullable()->change();
            $table->string('rua')->nullable()->change();
            $table->string('numero')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('empresas_enderecos', function (Blueprint $table) {
            $table->string('cep')->nullable(false)->change();
            $table->string('rua')->nullable(false)->change();
            $table->string('numero')->nullable(false)->change();
        });
    }
};
