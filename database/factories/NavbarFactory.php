<?php

namespace Database\Factories;

use App\Models\Navbar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NavbarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Navbar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Array containing Navbar data
        $navbarData = [
            ['title' => 'Home', 'link' => '#Home'],
            ['title' => 'Skills', 'link' => '#Skills'],
            ['title' => 'Projects', 'link' => '#Projects'],
            ['title' => 'Testimonials', 'link' => '#Testimonials'],
            ['title' => 'Contact Us', 'link' => '#ContactUs'],
        ];

        // Use iteration counter to select each record sequentially
        static $index = 0;

        return $navbarData[$index++];
    }
}
