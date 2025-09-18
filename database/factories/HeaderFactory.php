<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Header>
 */
class HeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'Headings' => "Hi! I'm Umair",
            'Description' => $this->faker->paragraph(),
            'MainImage' => 'images/1.svg', // Example image file name
            'cv' => 'cv/cv.pdf', // Example CV file name

        ];
    }
}
