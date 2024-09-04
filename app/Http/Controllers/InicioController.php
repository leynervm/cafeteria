<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{

    public function index()
    {
        return view('dashboard');
    }

    public function show(Order $order)
    {
        return view('admin.index', compact('order'));
    }

    public function pedidos()
    {
        $orders = Order::whereHas('pedidos', function ($query) {
            $query->where('status', 0);
            $query->whereHas('producto', function ($query) {
                $query->whereIn('category_id', Auth::user()->categories->pluck('id'));
            });
        })->with(['pedidos' => function ($query) {
            $query->where('status', 0);
            $query->with(['producto' => function ($query) {
                $query->whereIn('category_id', Auth::user()->categories->pluck('id'));
            }]);
        }])->where('status', 0)->orderBy('id', 'asc')->get();

        return response()->json($orders);
    }
}
