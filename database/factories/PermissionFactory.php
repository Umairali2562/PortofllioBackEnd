<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    private $permissionIndex = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $permissions = [
            'Create Permissions',
            'View Permissions',
            'Update Permissions',
            'Delete Permissions',
            'Create Roles',
            'View Roles',
            'Update Roles',
            'Delete Roles',
            'Create Users',
            'View Users',
            'Update Users',
            'Delete Users',
            'Create Projects',
            'View Projects',
            'Delete Projects',
            'Update Projects',
            'Create Navbar',
            'View Navbar',
            'Update Navbar',
            'Delete Navbar',
            'Create Header',
            'View Header',
            'Update Header',
            'Delete Header',
            'Create Testimonials',
            'View Testimonials',
            'Update Testimonials',
            'Delete Testimonials',
            'View ContactUs',
            'Delete ContactUs',
        ];

        $name = $permissions[$this->permissionIndex];
        $this->permissionIndex = ($this->permissionIndex + 1) % count($permissions);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
