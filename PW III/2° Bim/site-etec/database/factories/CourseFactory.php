<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Administração',
            'Desenvolvimento de Sistemas',
            'Logística',
            'Recursos Humanos',
            'Contabilidade',
            'Serviços Jurídicos',
            'Marketing',
            'Informática para Internet',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['tecnico', 'm-tec', 'ams']),
            'image_url' => null,
            'duration' => $this->faker->randomElement(['3 Semestres', '2 Anos', '1 Ano']),
            'period' => $this->faker->randomElement(['Manhã', 'Tarde', 'Noite', 'Manhã e Tarde', 'Tarde e Noite']),
        ];
    }
}
