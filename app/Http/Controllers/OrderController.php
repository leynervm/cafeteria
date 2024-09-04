<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Order;
use App\Models\Producto;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function create(Mesa $mesa)
    {
        return view('admin.orders.create', compact('mesa'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function cocina()
    {
        return view('admin.cocina.index');
    }
}
