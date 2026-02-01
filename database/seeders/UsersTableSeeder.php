<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nome' => 'Daniel',
                'email' => 'daniel.maia@ifnmg.edu.br',
                'senha' => bcrypt('123456'),
                'created_at' => now(),
            ],
            [
                'nome' => 'Usuario Teste',
                'email' => 'user@teste.com.br',
                'senha' => bcrypt('123456'),
                'created_at' => now(),
            ]
        ]);
    }
}
