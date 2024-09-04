<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => now(),
            'document' => $this->faker->randomNumber(8),
            'name' => $this->faker->name(),
            'direccion' => $this->faker->streetAddress(),
            'telefono' => $this->faker->randomNumber(9),
            'dateparty' => $this->faker->date(),
            'status' => 0,
        ];
    }
}
