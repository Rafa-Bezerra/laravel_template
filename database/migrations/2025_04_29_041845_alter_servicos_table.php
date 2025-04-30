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
        Schema::table('orcamentos_servicos', function (Blueprint $table) {
            $table->dateTime('data', precision: 0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orcamentos_servicos', function (Blueprint $table) {
            $table->dropColumn(['data']);
        });
    }
};
