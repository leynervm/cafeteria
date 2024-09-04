<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $users = User::all();
        $productos = Producto::all();
        $orders = Order::all();

        $precio =  $this->faker->numberBetween(3, 25);
        $cantidad = 1;

        return [
            'date' => now('America/Lima'),
            'cantidad' => $cantidad,
            'price' => $precio,
            'igv' => 0,
            'otros' => 0,
            'importe' => $precio * $cantidad,
            'detalle' => null,
            'status' => 0,
            'user_id' => $this->faker->randomElement($users)->id,
            'producto_id' => $this->faker->randomElement($productos)->id,
            'order_id' => $this->faker->randomElement($orders)->id,
        ];
    }
}
