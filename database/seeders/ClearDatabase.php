<?php

namespace Database\Seeders;

use App\Models\Actions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDatabase extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orcamentos_itens')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE orcamentos_itens AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('compras_itens')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE compras_itens AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('materiais')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE materiais AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('grupos_de_material')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE grupos_de_material AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('orcamentos_comissoes')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE orcamentos_comissoes AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('orcamentos_servicos')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE orcamentos_servicos AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('orcamentos_socios')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE orcamentos_socios AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('orcamentos')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE orcamentos AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('compras')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE compras AUTO_INCREMENT = 1;'); // Reseta o ID
        
        DB::table('estoque')->delete(); // Remove registros dependentes
        DB::statement('ALTER TABLE estoque AUTO_INCREMENT = 1;'); // Reseta o ID
    }
}
