<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Events\LoadPedidos;
use App\Models\Category;
use App\Models\Detallenota;
use App\Models\Notapedido;
use App\Models\Order;
use App\Rules\ValidateMesaDisonible;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CreateOrder extends Component
{
    use WithPagination;

    public $producto, $mesa;
    public $cliente;
    public $search = '';
    public $items = [];

    protected $queryString = [
        'search' => ['except' => '']
    ];

    protected $listeners = ['renderpedidos' => 'render', 'render'];

    protected function rules()
    {
        return [
            'mesa.id' => ['required', 'integer', 'min:1', 'exists:mesas,id', new ValidateMesaDisonible],
            'cliente' => ['required'],
            'items' => ['required', 'array', 'min:1']
        ];
    }

    public function render()
    {
        // $productos = Producto::orderBy('name', 'asc')->get();
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
        $pedidos = Notapedido::where('user_id', Auth::user()->id)->paginate();
        return view('livewire.admin.orders.create-order', compact('pedidos', 'categories'));
    }

    public function updatingSearch()
    {
        $this->dispatchBrowserEvent('remove-active');
    }

    public function save()
    {

        $this->cliente = trim($this->cliente);
        $this->items = Notapedido::where('user_id', Auth::user()->id)->get();
        $this->validate();
        DB::beginTransaction();

        try {
            $order = Order::create([
                'date' => now('America/Lima'),
                'name' => $this->cliente,
                'mesa_id' => $this->mesa->id,
                'user_id' => Auth::user()->id
            ]);

            foreach ($this->items as $item) {
                $pedido = $order->pedidos()->create([
                    'date' => now('America/Lima'),
                    'cantidad' => $item->cantidad,
                    'price' => $item->price,
                    'igv' => 0,
                    'otros' => 0,
                    'importe' => $item->price * $item->cantidad,
                    'detalle' => $item->detalle,
                    'user_id' => Auth::user()->id,
                    'producto_id' => $item->producto_id,
                ]);

                if (count($item->detallenotas)) {
                    foreach ($item->detallenotas as $a) {
                        $pedido->pedidoitems()->create([
                            'cantidad' => $a->cantidad,
                            'price' => $a->price,
                            'importe' => $a->price * $a->cantidad,
                            'agregado_id' => $a->agregado_id
                        ]);
                    }
                }
            }

            Notapedido::where('user_id', Auth::user()->id)->delete();
            $this->mesa->status = 1;
            $this->mesa->save();
            DB::commit();
            $this->resetValidation();
            $this->emit('created');
            // broadcast(new MessageSent("Nueva orden registrada"));
            try {
                broadcast(new LoadPedidos([
                    "mensaje" => 'Se aperturó una nueva orden en la mesa : ' . $this->mesa->name
                ]));
            } catch (BroadcastException $e) {
                $this->emit('sockets');
            }
            $this->reset();
            return redirect()->route('admin');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
