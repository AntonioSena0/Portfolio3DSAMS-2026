<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@etec.com',
            'password' => '12345678',
            'role' => 'admin',
        ]);

        $this->call([
            CourseSeeder::class,
        ]);
    }
}
