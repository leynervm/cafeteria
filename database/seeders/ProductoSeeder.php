<?php

namespace Database\Seeders;

use App\Models\Agregado;
use App\Models\Category;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Location;
use App\Models\Mesa;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Category::factory(4)->create();
        // Location::factory(4)->create();
        // Mesa::factory(6)->create();

        Category::create([
            'name' => 'PLATOS A LA CARTA'
        ]);
        Category::create([
            'name' => 'BEBIDAS FRESCAS'
        ]);
        Category::create([
            'name' => 'POSTRES'
        ]);
        Category::create([
            'name' => 'BEBIDAS CALIENTES'
        ]);

        Location::create([
            'name' => 'BALCÓN'
        ]);

        Location::create([
            'name' => 'TERRAZA'
        ]);

        Location::create([
            'name' => 'PRINCIPAL'
        ]);


        Coupon::factory(3)->create();
        Client::factory(5)->create();

        Mesa::create([
            'name' => 'MESA 01',
            'location_id' => 3
        ]);

        Mesa::create([
            'name' => 'MESA 02',
            'location_id' => 3
        ]);

        Mesa::create([
            'name' => 'BANCO 01',
            'location_id' => 1
        ]);

        Mesa::create([
            'name' => 'BANCO 02',
            'location_id' => 2
        ]);


        $agregados = Agregado::factory(3)->create();
        $productos = Producto::factory(20)->create();

        foreach ($productos as $producto) {
            $producto->agregados()->attach($agregados);
        }
    }
}
