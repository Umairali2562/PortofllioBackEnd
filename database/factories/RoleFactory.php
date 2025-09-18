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
        // List of roles
        $roles = [
            'Administrator' => 'administrator',
            'Manager' => 'manager',
            'Editor' => 'editor',
            'Viewer' => 'viewer',
            'Guest' => 'guest',
        ];

        // Instead of random, pick the first unused role each time
        static $index = 0;
        $names = array_keys($roles);
        $name = $names[$index % count($names)];
        $slug = $roles[$name];
        $index++;

        return [
            'name' => $name,
            'slug' => $slug,
        ];
    }
}
