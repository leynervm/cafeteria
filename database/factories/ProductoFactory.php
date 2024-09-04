<?php

namespace Database\Factories;

use App\Models\Agregado;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $categories = Category::all();
        $users = User::all();

        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomNumber(2, 2),
            'rendimiento' => $this->faker->numberBetween(3, 25),
            'imagen' => null,
            'unit' => 'NIU',
            'code' =>   Str::random(5),
            'category_id' => $this->faker->randomElement($categories)->id,
            'user_id' => $this->faker->randomElement($users)->id,
        ];
    }
}
