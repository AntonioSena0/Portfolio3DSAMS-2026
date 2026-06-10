<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@edulibre.test',
        ], [
            'name' => 'Admin EduLibre',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::updateOrCreate([
            'email' => 'aluno@edulibre.test',
        ], [
            'name' => 'Aluno EduLibre',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $categories = [
            ['name' => 'Programação', 'slug' => 'programacao', 'description' => 'Lógica, desenvolvimento web, banco de dados e fundamentos de software.', 'color' => '#4F46E5'],
            ['name' => 'Matemática', 'slug' => 'matematica', 'description' => 'Álgebra, geometria, estatística e preparação para provas.', 'color' => '#0EA5E9'],
            ['name' => 'Ciências', 'slug' => 'ciencias', 'description' => 'Biologia, física, química e pensamento científico aplicado.', 'color' => '#10B981'],
            ['name' => 'Humanas', 'slug' => 'humanas', 'description' => 'História, geografia, filosofia, sociologia e atualidades.', 'color' => '#F59E0B'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
