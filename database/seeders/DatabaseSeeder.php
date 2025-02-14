<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'RAFAEL BEZERRA BARBOSA',
            'email' => 'bezerrabarbosarafael@gmail.com',
            'password' => '$2y$12$Z6bAX23aMjL/xQO8Yml/IO9h8dzkAUSgHT8zcXhqNhItGtbrUp/Vm',
            'active' => 1,
        ]);
    }
}
