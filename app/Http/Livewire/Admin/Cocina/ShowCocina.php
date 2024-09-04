<?php

namespace App\Http\Livewire\Admin\Cocina;

use App\Models\Order;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowCocina extends Component
{

    // public $orders = [];
    // protected $listeners = ['echo:chat,MessageSent' => 'render'];

    public function mount()
    {
        // $this->orders = Order::whereHas('pedidos', function ($query) {
        //     $query->where('status', 0);
        //     $query->whereHas('producto', function ($query) {
        //         $query->whereIn('category_id', Auth::user()->categories->pluck('id'));
        //     });
        // })->with(['pedidos' => function ($query) {
        //     $query->where('status', 0);
        //     $query->with(['producto' => function ($query) {
        //         $query->whereIn('category_id', Auth::user()->categories->pluck('id'));
        //     }]);
        // }])->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function render()
    {

        // $orders = Order::with('pedidos')->where('status', 0)->get();
        // $categories = $user->categories->pluck('id');

        // $categories = Pedido::whereHas('producto.category', function ($query) {
        //     $query->whereIn('categories.id', Auth::user()->categories->pluck('id'));
        //     $query->where('status', 0);
        // })->with(['producto.category'])->pluck('id');

        // $ordersFilters = $orders;
        //PEDIDOS SIRVE
        // $ordersFilters = Order::with(['pedidos' => function ($query) {
        //     $query->whereIn('id', [4, 6, 7]);
        // }])
        //     ->where('status', 0)
        // ->get();

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

        // $ordersFilters = Order::with(['pedidos' => function ($query) {
        //     $query->where('status', 0);
        //     $query->with(['producto' => function ($query) {
        //         $query->whereIn('category_id', Auth::user()->categories->pluck('id'));
        //     }]);
        // }])->where('status', 0)->get();

        return view('livewire.admin.cocina.show-cocina', compact('orders'));
    }

    public function confirmar_todo(Order $order)
    {
        $order->pedidos()->where('status', 0)->update(['status' => 1]);
        $this->emit('updated');
    }

    public function confirmar_pedido(Pedido $pedido)
    {
        $pedido->status = 1;
        $pedido->save();
        $this->emit('updated');
    }
}
