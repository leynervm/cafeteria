<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => Str::random(16),
            'descuento' => $this->faker->randomDigit(),
            'limit' => $this->faker->randomDigit(),
            'start' => $this->faker->date(),
            'end' => $this->faker->date(),
            'status' => 0,
        ];
    }
}
