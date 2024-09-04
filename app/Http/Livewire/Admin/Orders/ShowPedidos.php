<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Events\LoadPedidos;
use App\Models\Detallenota;
use App\Models\Notapedido;
use App\Models\Pedido;
use App\Models\Pedidoitem;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowPedidos extends Component
{

    public $order;

    protected $listeners = [
        'renderpedidos' => 'render',
        'deleteitemnota', 'deleteagregadonota', 'deletenotapedidos', 'delete', 'deleteagregado'
    ];

    public function mount($order)
    {
        $this->order = $order;
    }

    public function render()
    {
        if ($this->order == null) {
            $pedidos = Notapedido::where('user_id', Auth::user()->id)->paginate();
        } else {
            $pedidos = Pedido::where('order_id', $this->order->id)->paginate();
        }

        return view('livewire.admin.orders.show-pedidos', compact('pedidos'));
    }

    public function delete(Pedido $pedido)
    {
        $pedido->pedidoitems()->delete();
        $pedido->deleteOrFail();
        $this->emit('deleted');
        try {
            broadcast(new LoadPedidos([
                "mensaje" => 'Se eliminó el pedido : ' . $pedido->producto->name . ' perteneciente a la mesa : ' . $pedido->order->mesa->name
            ]));
        } catch (BroadcastException $e) {
            $this->emit('sockets');
        }
    }

    public function deleteagregado(Pedidoitem $pedidoitem)
    {
        $pedidoitem->deleteOrFail();
        $this->emit('deleted');
        try {
            broadcast(new LoadPedidos([
                "mensaje" => 'Se eliminó un agregado de : ' . $pedidoitem->pedido->producto->name . ' perteneciente a la mesa : ' . $pedidoitem->pedido->order->mesa->name
            ]));
        } catch (BroadcastException $e) {
            $this->emit('sockets');
        }
    }

    public function deleteitemnota(Notapedido $notapedido)
    {

        DB::beginTransaction();
        try {
            $notapedido->detallenotas()->delete();
            $notapedido->deleteOrFail();
            DB::commit();
            $this->emit('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteagregadonota(Detallenota $detallenota)
    {
        $detallenota->deleteOrFail();
        $this->emit('deleted');
    }

    public function deletenotapedidos()
    {

        DB::beginTransaction();
        try {
            Notapedido::where('user_id', Auth::user()->id)->delete();
            DB::commit();
            $this->emit('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
