<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Events\LoadPedidos;
use App\Models\Category;
use App\Models\Notapedido;
use App\Models\Producto;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowCartaProductos extends Component
{

    public $open = false;
    public $producto;
    public $recentId = [];
    public $recentPedidos = [];
    public $order;
    public $search = '';

    public $pedidos = [];
    public $cantidad = 0;

    public $pedidosConfirmar = [];

    protected $queryString = [
        'search' => [
            'except' => '',
            'as' => 'buscar'
        ]
    ];

    protected $listeners = ['confirmarPedido', 'save_pedido'];

    public function mount($order)
    {
        $this->order = $order;
        $this->producto = new Producto();
    }

    public function render()
    {
        $productos = Producto::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->with('productos');

        if (trim($this->search) !== '') {
            $categories->whereHas('productos', function ($query) {
                $query->where('name', 'ilike', '%' . $this->search . '%');
            })
                ->with(['productos' => function ($query) {
                    $query->where('name', 'ilike', '%' . $this->search . '%');
                }]);
        }

        $categories = $categories->get();
        return view('livewire.admin.orders.show-carta-productos', compact('productos', 'categories'));
    }

    public function updatingSearch()
    {
        $this->dispatchBrowserEvent('remove-active');
    }

    public function addpedido($formData, Producto $producto)
    {

        $this->producto = $producto;
        $this->cantidad = $formData["cantidad"];
        $this->reset(['pedidos']);
        $this->validate([
            'cantidad' => ['required', 'integer', 'min:1']
        ]);

        for ($i = 0; $i < $formData["cantidad"]; $i++) {
            $this->pedidos[] = [
                'producto_id' => $producto->id,
                'cantidad' => 1,
                'price' => $producto->price,
                'igv' => 0,
                'detalle' => null,
                'agregados' => [],
            ];
        }

        $this->open = true;
    }

    public function savepedidos()
    {

        $this->validate([
            'pedidos' => ['required', 'array', 'min:1'],
            'pedidos.*.producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'pedidos.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        DB::beginTransaction();

        try {

            $pedidos = response()->json($this->pedidos)->getData();

            foreach ($pedidos as $item) {
                if ($this->order) {
                    $pedido = $this->order->pedidos()->create([
                        'date' => now("America/Lima"),
                        'cantidad' => 1,
                        'price' => $item->price,
                        'igv' => $item->igv,
                        'otros' => 0,
                        'importe' => $this->producto->price,
                        'detalle' => $item->detalle,
                        'status' => 0,
                        'producto_id' => $item->producto_id,
                        'user_id' => Auth::user()->id
                    ]);
                } else {
                    $notapedido = Notapedido::create([
                        'date' => now("America/Lima"),
                        'cantidad' => 1,
                        'price' =>  $item->price,
                        'igv' => $item->igv,
                        'detalle' => $item->detalle,
                        'producto_id' => $item->producto_id,
                        'user_id' => Auth::user()->id
                    ]);
                }

                $agregados = $item->agregados;

                if ($agregados !== []) {
                    foreach ($agregados as $a) {
                        if ($a) {

                            $agregado = $this->producto->agregados()->find($a);

                            if ($this->order) {
                                $pedido->pedidoitems()->updateOrCreate(['agregado_id' => $a], [
                                    'cantidad' => 1,
                                    'price' => $agregado->price,
                                    'importe' => $agregado->price,
                                ]);
                            } else {
                                $notapedido->detallenotas()->updateOrCreate(['agregado_id' => $a], [
                                    'cantidad' => 1,
                                    'price' => $agregado->price,
                                    'igv' => 0
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            $this->resetValidation();
            $this->reset(['open']);
            $this->emit('created');
            $this->emit('renderpedidos');
            if ($this->order) {
                // event(new MessageSent("Pedido agregado a la orden"));
                // broadcast(new MessageSent("Pedido agregado a la orden"));
                try {
                    broadcast(new LoadPedidos([
                        "mensaje" => 'Se agregaron ' . count($this->pedidos) . ' nuevos pedidos perteneciente a la mesa : ' . $this->order->mesa->name
                    ]));
                } catch (BroadcastException $e) {
                    $this->emit('sockets');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($indice)
    {
        unset($this->pedidos[$indice]);
        $this->pedidos = array_values($this->pedidos);
        $this->cantidad = count($this->pedidos);
        if (count($this->pedidos) == 0) {
            $this->open = false;
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('remove-active');
    }
}
