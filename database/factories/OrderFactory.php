<?php

namespace Database\Factories;

use App\Models\Mesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $mesas = Mesa::all();
        $users = User::all();

        return [
            'date' => now('America/Lima'),
            'name' => $this->faker->name(),
            'status' => 0,
            'mesa_id' => $this->faker->randomElement($mesas)->id,
            'user_id' => $this->faker->randomElement($users)->id,
        ];
    }
}
