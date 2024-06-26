<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => 'gallery-'.fake()->uuid(),
            'image' => 'https://picsum.photos/800',
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}
