<?php

namespace App\Http\Livewire\Admin;

use App\Models\Mesa;
use App\Models\Order;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{

    protected $listeners = ['echo:chat,MessageSent' => 'render', 'render'];

    public $selected;
    public $order;

    public function mount()
    {
        $this->order = new Order();
    }

    public function render()
    {
        $mesas = Auth::user()->mesas()->orderBy('name', 'asc')->get();
        return view('livewire.admin.index',  compact('mesas'));
    }

    public function entregar_pedido(Pedido $pedido)
    {
        $pedido->status = 2;
        $pedido->save();
        $this->order->refresh();
        $this->emit('updated');
        // $this->order = $pedido->order->mesa->orders()->where('status', 0)->get();
    }

    public function entregar_todo(Order $order)
    {
        $order->pedidos()->where('status', 1)->update(['status' => 2]);
        $this->order->refresh();
        $this->emit('updated');
    }

    public function loadorder(Mesa $mesa)
    {
        $this->order = $mesa->orders()->where('status', 0)->first();
    }
}
