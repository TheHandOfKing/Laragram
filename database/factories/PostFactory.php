<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => rtrim($this->faker->sentence(rand(5, 10)), "."),
            'description' => $this->faker->paragraphs(rand(3, 7), true),
            'location' => 'Belgrade/Serbia',
            'views' => rand(0, 10),
        ];
    }
}
