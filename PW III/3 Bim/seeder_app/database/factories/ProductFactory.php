<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory(),
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name).'-'.fake()->unique()->lexify('????????'),
            'description' => fake()->paragraph(2),
            'price' => fake()->randomFloat(2, 9.90, 999.90),
            'stock' => fake()->numberBetween(0, 200),
            'active' => fake()->boolean(90),
        ];
    }
}
