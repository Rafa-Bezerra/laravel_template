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
        Schema::table('empresas_contatos', function (Blueprint $table) {
            $table->string('contato')->nullable()->change();
            $table->string('fone')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('empresas_contatos', function (Blueprint $table) {
            $table->string('contato')->nullable(false)->change();
            $table->string('fone')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
};
