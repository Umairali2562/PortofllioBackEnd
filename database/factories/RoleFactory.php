<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Define specific roles
        $roles = [
            'Administrator' => 'administrator',
            'Manager' => 'manager',
            'Editor' => 'editor',
            'Viewer' => 'viewer',
            'Guest' => 'guest',
        ];

        return [
            'name' => $this->faker->unique()->randomElement(array_keys($roles)),
            'slug' => function (array $attributes) use ($roles) {
                return $roles[$attributes['name']];
            },
        ];
    }
}
