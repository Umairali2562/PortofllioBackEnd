<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Header;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

       \App\Models\Testimonials::factory(10)->create();
       \App\Models\Navbar::factory(5)->create();

       \App\Models\Permission::factory()->count(30)->create();
        Header::factory()->count(1)->create(); // Change count as needed
       \App\Models\Role::factory()->count(5)->create();
     //  \App\Models\User::factory()->count(2)->create();
        User::create([
            'name' => 'Umair',
            'email' => 'umairali2562@gmail.com',
            'password' => bcrypt('123'),
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Hassan',
            'email' => 'Hassan@gmail.com',
            'password' => bcrypt('123'),
            'remember_token' => Str::random(10),
        ]);


    }
}
