<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AdminUser extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Rafael Bezerra Barbosa',
            'email' => 'bezerrabarbosarafael@gmail.com',
            'password' => '$2y$12$Z6bAX23aMjL/xQO8Yml/IO9h8dzkAUSgHT8zcXhqNhItGtbrUp/Vm',
            'active' => 1,
        ]);
    }
}
