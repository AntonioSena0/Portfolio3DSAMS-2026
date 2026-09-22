<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@seederapp.local'],
            [
                'name' => 'Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        \App\Models\User::factory(10)->create();
    }
}
