<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->randomElement([
            'Feira Tecnológica da ETEC Zona Leste',
            'Semana Paulo Freire',
            'Mostra de Projetos Integradores',
            'Processo Seletivo Vestibulinho',
            'Palestra sobre Mercado de Tecnologia',
            'Encontro de Ex-Alunos',
            'Oficina de Currículo e Entrevista',
            'Maratona de Programação',
        ]);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(3, true),
            'excerpt' => $this->faker->sentence,
            'event_date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'image_url' => null,
            'category' => $this->faker->randomElement(['evento', 'noticia', 'destaque']),
        ];
    }
}
