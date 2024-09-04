<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgregadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $users = User::all();

        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->numberBetween(1, 8),
            'imagen' => null,
            'unit' => 'NIU',
            'user_id' => $this->faker->randomElement($users)->id,
        ];
    }
}
