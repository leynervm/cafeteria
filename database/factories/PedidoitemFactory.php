<?php

namespace Database\Factories;

use App\Models\Agregado;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoitemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $agregados = Agregado::all();
        $pedidos = Pedido::all();
        $users = User::all();

        $precio =  $this->faker->numberBetween(1, 8);
        $cantidad = 1;

        return [
            'cantidad' => $cantidad,
            'price' => $precio,
            'importe' => $precio * $cantidad,
            'status' => 0,
            'agregado_id' => $this->faker->randomElement($agregados)->id,
            'pedido_id' => $this->faker->randomElement($pedidos)->id,
        ];
    }
}
