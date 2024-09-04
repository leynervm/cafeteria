<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class MesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $locations = Location::all();

        return [
            'name' => $this->faker->name(),
            'status' => 0,
            'location_id' => $this->faker->randomElement($locations)->id,
        ];
    }
}
