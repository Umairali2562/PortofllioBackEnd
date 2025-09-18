<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravolt\Avatar\Avatar;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonials>
 */
class TestimonialsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $jobTitles = ['Developer', 'Designer', 'Project Manager', 'Owner', 'CEO', 'CTO', 'Software Engineer', 'Data Scientist'];
        $avatar = new Avatar();
        return [
            'name'=>$this->faker->name,
            'reviews' => $this->faker->paragraph,
            'image_url' => $this->faker->imageUrl(200, 200, 'people'),
            'job_titles' => $this->faker->randomElement($jobTitles),
        ];
    }
}

