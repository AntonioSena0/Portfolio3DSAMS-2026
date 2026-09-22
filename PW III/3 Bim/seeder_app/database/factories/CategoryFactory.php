<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name).'-'.fake()->unique()->lexify('??????'),
            'description' => fake()->sentence(12),
            'active' => true,
        ];
    }
}
