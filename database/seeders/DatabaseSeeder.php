<?php

namespace Database\Seeders;

use App\Models\Header;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Testimonials and Navbar entries
        \App\Models\Testimonials::factory(10)->create();
        \App\Models\Navbar::factory(5)->create();
        Header::factory()->count(1)->create();

        // Create Permissions
        $permissions = Permission::factory()->count(30)->create();

        // Create Roles
        $roles = Role::factory()->count(5)->create();

        // Create Users
        $umair = User::firstOrCreate(
            ['email' => 'umairali2562@gmail.com'],
            [
                'name' => 'Umair',
                'password' => bcrypt('123'),
                'remember_token' => Str::random(10),
            ]
        );

        User::firstOrCreate(
            ['email' => 'Waqar@gmail.com'],
            [
                'name' => 'Waqar',
                'password' => bcrypt('123'),
                'remember_token' => Str::random(10),
            ]
        );

        // Assign all permissions to Umair
        $umair->permissions()->sync($permissions->pluck('id')->toArray());

        // Assign all permissions to Administrator role
        $adminRole = Role::where('slug', 'administrator')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync($permissions->pluck('id')->toArray());

            // Assign Umair the Administrator role
            $umair->roles()->sync([$adminRole->id]);
        }
    }
}
