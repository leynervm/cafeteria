<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Pedido;
use App\Models\Pedidoitem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Pedido::factory(10)->create();
        // Pedidoitem::factory(5)->create();

        Order::factory(5)->create()->each( function ($order) {

            $numItems = rand(1, 3);

            for ($j = 0; $j < $numItems; $j++) {
                $pedido = $order->pedidos()->save(Pedido::factory()->make());

                $numAgreg = rand(0, 3);

                for ($c = 0; $c < $numAgreg; $c++) {
                    $pedido->pedidoitems()->save(Pedidoitem::factory()->make());
                }
                
            }
        });
    }
}
