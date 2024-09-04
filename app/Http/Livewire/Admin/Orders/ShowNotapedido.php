<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Helpers\ConvertAmountString;
use App\Helpers\generarXML;
use App\Helpers\GetClientSunat;
use App\Models\Category;
use App\Models\Client;
use App\Models\Comprobante;
use App\Models\Coupon;
use App\Models\Empresa;
use App\Models\Formapago;
use App\Models\Othercosto;
use App\Models\Producto;
use App\Models\Serie;
use App\Rules\ValidateDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class ShowNotapedido extends Component
{

    public $order;
    public $electronicos = [];
    public $selectedPedidos = [];
    public $amountPayment = 0;
    public $amountAgregados = 0;
    public $empresa;
    public $comprobante;
    public $othercosto;

    public $payment = 'CONTADO';
    public $comprobante_id, $document, $name, $direccion,
        $formapago_id, $othercosto_id, $coupon;

    public $validatedCoupon = false;
    public $amountCoupon = 0;
    public $percentCoupon = 0;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'comprobante_id' => ['required', 'integer', 'exists:series,id'],
            'document' => ['required', 'numeric', 'min:8', new ValidateDocument($this->comprobante->code)],
            'name' => ['required', 'string'],
            'direccion' => ['required', 'string'],
            'payment' => ['required', 'string'],
            'formapago_id' => ['required', 'integer', 'exists:formapagos,id'],
            'othercosto_id' => ['nullable', 'integer', 'exists:othercostos,id'],
            'coupon' => ['nullable', 'string', 'min:5', 'max:12'],
            'order.id' => ['required', 'integer', 'exists:orders,id'],
            'empresa.id' => ['required', 'integer', 'exists:empresas,id'],
            'selectedPedidos' => ['required', 'array', 'min:1'],
        ];
    }

    public function mount()
    {

        $this->comprobante = new Serie();
        $this->empresa = new Empresa();
        $this->selectedPedidos = $this->order->pedidos()->where('status', 2)->pluck('id')->toArray();
        $pedidos = $this->order->pedidos()->whereIn('id',  $this->selectedPedidos);
        $this->amountPayment = $pedidos->sum('price');
        $this->comprobante = Serie::where('code', '03')->where('default', 1)->first();

        $this->electronicos = $this->order->comprobantes;

        if ($this->comprobante) {
            $this->comprobante_id = $this->comprobante->id;
        }

        if (count($pedidos->get())) {
            foreach ($pedidos->get() as $item) {
                $this->amountAgregados += $item->pedidoitems->sum('price');
            }
        }

        $formaPago = Formapago::where('default', 1)->first();
        if ($formaPago->id) {
            $this->formapago_id = $formaPago->id;
        }
    }

    public function render()
    {
        $productos = Producto::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $comprobantes = Serie::whereNotIn('code', ['07'])->orderBy('name', 'asc')->get();
        $othercostos = Othercosto::orderBy('name', 'asc')->get();
        $formapagos = Formapago::orderBy('name', 'asc')->get();
        return view('livewire.admin.orders.show-notapedido', compact('productos', 'categories', 'comprobantes', 'othercostos', 'formapagos'));
    }

    public function updatedOthercostoId($value)
    {
        $this->reset(['othercosto', 'amountCoupon']);

        if (trim($value) !== "") {
            $this->othercosto = Othercosto::findorFail($value);
            $this->amountCoupon = (($this->amountPayment + $this->amountAgregados + $this->othercosto->price) * $this->percentCoupon) / 100;
        } else {
            $this->amountCoupon = (($this->amountPayment + $this->amountAgregados) * $this->percentCoupon) / 100;
        }
    }

    // public function updatedComprobanteId($value)
    // {
    //     $this->comprobante = Serie::find($value);
    // }

    public function updatedSelectedPedidos()
    {
        $this->amountPayment = $this->order->pedidos()
            ->where('status', 2)->whereIn('id',  $this->selectedPedidos)->sum('price');
        $this->amountAgregados = 0;

        $pedidos = $this->order->pedidos()->where('status', 2)->whereIn('id',  $this->selectedPedidos)->get();

        if (count($pedidos)) {
            foreach ($pedidos as $item) {
                $this->amountAgregados += $item->pedidoitems->sum('price');
            }
        }
    }

    public function savePayment()
    {

        $this->empresa = Empresa::where('default', 1)->first();
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->coupon = trim($this->coupon);
        $this->comprobante = Serie::find($this->comprobante_id);
        $leyendaStr = new ConvertAmountString();
        $this->validate();
        DB::beginTransaction();

        try {

            $client = Client::where('document', $this->document)->first();

            if (!$client) {
                $client = Client::create([
                    'date' => now('America/Lima'),
                    'document' => $this->document,
                    'name' => $this->name,
                    'direccion' => $this->direccion,
                    'telefono' => null,
                    'dateparty' => null,
                    'status' => 0
                ]);
            }

            $countSeries = Comprobante::where('codeserie', $this->comprobante->serie)->count();
            $correlativo = $countSeries == 0 ? 1 : $countSeries + 1;

            $gravado = 0;
            $exonerado = 0;
            $descuento = 0;
            $igv = 0;
            $otros = 0;

            $coupon_id = null;
            $othercosto = $this->othercosto ? $this->othercosto->price : 0;
            $suma = ($this->amountPayment + $this->amountAgregados + $othercosto);

            if ($this->validatedCoupon) {

                $coupon = Coupon::whereDate('start', '<=', Carbon::now('America/Lima')->format('Y-m-d'))
                    ->whereDate('end', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
                    ->where('status', 0)->where('limit', '>', 0)
                    ->where('code', trim($this->coupon))->first();

                if ($coupon) {
                    $coupon_id = $coupon->id;
                    $descuento = ($suma * $this->percentCoupon) / 100;
                    $suma = $suma - $descuento;
                    $limit = floatval($coupon->limit) - 1;
                    $statuscoupon = $limit > 0 ? 0 : 1;

                    $coupon->update(['limit' => $limit, 'status' => $statuscoupon]);
                    // $coupon->save();
                }
            }

            // if ($this->tribute->code == 9997) {
            $exonerado = $suma;
            // } else {
            //     $igv = ($suma * 18) / 100;
            //     $gravado = $suma - $igv;
            // }           

            $comprobante = Comprobante::create([
                'date' => now('America/Lima'),
                'expire' => Carbon::now('America/Lima')->tomorrow(),
                'correlativo' => $correlativo,
                'codeserie' => $this->comprobante->serie,
                'seriecompleta' => $this->comprobante->serie . '-' . $correlativo,
                'gravado' => $gravado,
                'exonerado' => $exonerado,
                'descuento' => $descuento,
                'igv' => $igv,
                'total' => $suma,
                'otros' => $otros,
                'leyenda' => $leyendaStr->convertString(abs($suma), '', 'NUEVOS SOLES'),
                'payment' => $this->payment,
                'hash' => null,
                'codesunat' => null,
                'descripcionsunat' => null,
                'moneda' => $this->empresa->moneda,
                'percent' => 18,
                'status' => 0,
                'direccion' => $this->direccion,
                'formapago_id' => $this->formapago_id,
                'coupon_id' => $coupon_id,
                'client_id' => $client->id,
                'serie_id' => $this->comprobante->id,
                'order_id' => $this->order->id,
                'empresa_id' => $this->empresa->id,
                'user_id' => Auth::user()->id
            ]);

            // TENER EN CUENTA EL DESCUENTO EN OS ITEMS DEL COMPROBANTE SINO VA DESCUADRAR
            $indice = 1;
            $pedidos = $this->order->pedidos()->whereIn('id',  $this->selectedPedidos);
            $totalpedidos = $this->order->pedidos()->count();

            if (count($pedidos->get())) {
                foreach ($pedidos->get() as $item) {

                    $amountAgregados = 0;
                    $amountAgregados = $item->pedidoitems->sum('price');
                    $descripcionAgregados = '';

                    if (count($item->pedidoitems)) {
                        foreach ($item->pedidoitems as $agreg) {
                            $descripcionAgregados .= ' + ' . $agreg->agregado->name . '(S/. ' . $agreg->price . ')';
                        }
                    }

                    $comprobante->comprobanteitems()->create([
                        'item' => $indice,
                        'descripcion' => $item->producto->name . $descripcionAgregados,
                        'unit' => $item->producto->unit,
                        'code' => $item->producto->code,
                        'cantidad' =>  $item->cantidad,
                        'price' => $item->price + $amountAgregados,
                        'igv' => 0,
                        'importe' => $item->price + $amountAgregados,
                        'pedido_id' => $item->id,
                    ]);
                    $indice++;
                }

                $pedidos->update(['status' => 3]);
                if ($totalpedidos == $this->order->pedidos()->where('status', 3)->count()) {
                    $this->order->status = 1;
                    $this->order->save();
                    $this->order->mesa->status = 0;
                    $this->order->mesa->save();
                }
            } else {
                $this->addError('selectedPedidos', 'No existem pedidos para generar comprobante');
                DB::rollBack();
                return false;
            }

            if ($this->othercosto) {
                $comprobante->comprobanteitems()->create([
                    'item' => $indice,
                    'descripcion' => $this->othercosto->name,
                    'cantidad' =>  1,
                    'price' => $this->othercosto->price,
                    'igv' => 0,
                    'unit' => $this->othercosto->unit,
                    'code' => $this->othercosto->code,
                    'importe' => $this->othercosto->price,
                    'pedido_id' => null,
                ]);
            }

            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['order', 'comprobante', 'payment', 'formapago_id', 'empresa']);
            $this->render();
            return redirect()->route('admin.orders.show', $this->order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function verificarcupon()
    {
        $this->coupon = trim($this->coupon);
        $validateData = $this->validate([
            'coupon' => ['required', 'string', 'min:5', 'max:12']
        ]);

        $cupon = Coupon::whereDate('start', '<=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->whereDate('end', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->where('status', 0)->where('limit', '>', 0)
            ->where('code', trim($this->coupon))->first();

        if ($cupon) {
            $this->resetValidation(['coupon']);
            $this->validatedCoupon = true;
            $this->percentCoupon = $cupon->descuento;
            $this->emit('verified');
        } else {
            $this->emit('notfound');
            $this->addError('coupon', 'cupón no disponible');
        }

        $othercosto = $this->othercosto ? $this->othercosto->price : 0;
        $this->amountCoupon = (($this->amountPayment + $this->amountAgregados + $othercosto) * $this->percentCoupon) / 100;
    }

    public function resetcupon()
    {
        $this->reset(['validatedCoupon', 'amountCoupon', 'coupon']);
    }

    public function searchclient()
    {

        if (Str::length(trim($this->document)) == 8 || Str::length(trim($this->document)) == 11) {
            $client = new GetClientSunat();
            $response = $client->getClient(trim($this->document));

            if ($response->getData()) {
                $this->name = '';
                if ($response->getData()->success) {

                    $this->name = $response->getData()->name;
                    if (trim($response->getData()->direccion) !== '') {
                        $this->direccion = $response->getData()->direccion;
                    }
                } else {
                    // dd($response->getData());
                    $this->resetValidation(['document']);
                    $this->addError('document', $response->getData()->mensaje);
                }
            }
        } else {
            $this->resetValidation(['document']);
            $this->addError('document', 'Dígitos del documento no válidos.');
        }
    }
}
