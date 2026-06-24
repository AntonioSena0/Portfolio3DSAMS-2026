<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'M-TEC Desenvolvimento de Sistemas',
                'description' => 'Formação técnica para criar aplicações web, mobile e desktop com lógica de programação, banco de dados e práticas de engenharia de software.',
                'type' => 'tecnico',
                'duration' => '3 Anos',
                'period' => 'Manhã e Noite',
            ],
            [
                'name' => 'M-TEC Administração',
                'description' => 'Preparação para atuar em rotinas administrativas, planejamento, finanças, gestão de pessoas e processos organizacionais.',
                'type' => 'tecnico',
                'duration' => '3 Anos',
                'period' => 'Manhã, Tarde e Noite',
            ],
            [
                'name' => 'M-TEC Logística',
                'description' => 'Curso voltado à gestão de estoques, transporte, distribuição, compras e operações de cadeia de suprimentos.',
                'type' => 'tecnico',
                'duration' => '3 Anos',
                'period' => 'Manhã, Tarde e Noite',
            ],
            [
                'name' => 'M-TEC Recursos Humanos AMS',
                'description' => 'Desenvolvimento de competências para recrutamento, folha de pagamento, clima organizacional e legislação trabalhista.',
                'type' => 'ams',
                'duration' => '3 Anos',
                'period' => 'Tarde',
            ],
            [
                'name' => 'M-TEC AMS Desenvolvimento de Sistemas',
                'description' => 'Percurso integrado entre ensino médio, técnico e superior com foco em tecnologia, produto digital e empregabilidade.',
                'type' => 'ams',
                'duration' => '5 Anos',
                'period' => 'Tarde',
            ],
        ];

        foreach ($courses as $course) {
            Course::query()->updateOrCreate(
                ['slug' => Str::slug($course['name'])],
                [
                    ...$course,
                    'slug' => Str::slug($course['name']),
                    'image_url' => null,
                ],
            );
        }
    }
}
