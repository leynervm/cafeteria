<?php

namespace App\Http\Livewire\Admin\Comprobantes;

use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Helpers\generarXML;
use App\Models\Comprobante;
use App\Models\Serie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ShowComprobantes extends Component
{

    use WithPagination;

    public $open = false;
    public $comprobante;

    public function mount()
    {
        $this->comprobante = new Comprobante();
    }

    public function render()
    {
        $comprobantes = Comprobante::orderBy('date', 'desc')->paginate(10);
        return view('livewire.admin.comprobantes.show-comprobantes', compact('comprobantes'));
    }

    public function items(Comprobante $comprobante)
    {
        $this->comprobante =  $comprobante;
        $this->open = true;
    }

    public function sendsunat(Comprobante $comprobante)
    {

        $namecert = $comprobante->empresa->cert;
        // if ($comprobante->empresa->publickey && Storage::disk('local')->exists('empresa/pem/' . $publickey)) {
        // dd(Storage::disk('local')->path('/empresa/cert/' . $namecert));
        if (Storage::disk('local')->exists('empresa/cert/' . $namecert)) {
            if (!empty($comprobante->empresa->usuariosol) && !empty($comprobante->empresa->clavesol)) {

                $nombreXML = $comprobante->empresa->ruc . '-' . $comprobante->serie->code . '-' . $comprobante->seriecompleta;
                $ruta = 'xml/' . $comprobante->serie->code . '/';

                // if (!Storage::directoryExists(storage_path('app/' . $ruta))) {
                //     Storage::disk('local')->makeDirectory($ruta);
                // }
                if (!(Storage::disk('local')->exists('empresa/cert/'))) {
                    Storage::disk('local')->makeDirectory('empresa/cert/');
                }

                $xml = new createXML();
                if ($comprobante->serie->code == '07') {
                    $xml->comprobanteNotaCreditoXML($ruta . $nombreXML, $comprobante->empresa, $comprobante->client, $comprobante);
                } else {
                    $xml->comprobanteVentaXML($ruta . $nombreXML, $comprobante->empresa, $comprobante->client, $comprobante);
                }

                $this->dispatchBrowserEvent('created');

                $objApi = new SendXML();
                $response = $objApi->enviarComprobante($comprobante->empresa, $nombreXML, storage_path('app/empresa/cert/'), storage_path('app/' . $ruta), storage_path('app/' . $ruta));
                $mensaje = json_decode($response);

                $comprobante->hash = $mensaje->hash;
                $comprobante->codesunat = $mensaje->code;
                $comprobante->descripcionsunat = $mensaje->text;
                if ($mensaje->code == '0') {
                    $comprobante->status = 1;
                }
                $comprobante->save();
                $this->emit('alert', $mensaje);
                // dd($mensaje);
                // $this->dispatchBrowserEvent('created');

            } else {
                session()->flash('message', 'Configurar usuario y clave SOL de facturación.');
            }
        } else {
            session()->flash('message', 'Archivo de firma digital de facturación no existe.');
        }
        // } else {
        //     session()->flash('message', 'Clave pública de facturación no existe.');
        // }
    }

    public function anular(Comprobante $comprobante)
    {

        if (is_null($comprobante->codesunat)) {
            $comprobante->status = 2;
            $comprobante->save();
            $mensaje = ([
                'title' => 'Anulado correctamente',
                'text' => 'El comprobante ' . $comprobante->seriecompleta . ' se anuló correctamente.',
                'type' => 'success',
            ]);
        } else {

            $serienotacredito = Serie::where('code', '07')
                ->where('referencia', $comprobante->serie->code)->first();

            if ($serienotacredito) {

                DB::beginTransaction();
                try {

                    $correlativo = (int)($serienotacredito->contador) + 1;
                    $notacredito = Comprobante::create([
                        'date' => now('America/Lima'),
                        'expire' => Carbon::now('America/Lima')->tomorrow(),
                        'correlativo' => $correlativo,
                        'codeserie' => $serienotacredito->serie,
                        'seriecompleta' => $serienotacredito->serie . '-' . $correlativo,
                        'gravado' => $comprobante->gravado,
                        'exonerado' => $comprobante->exonerado,
                        'descuento' => $comprobante->descuento,
                        'igv' => $comprobante->igv,
                        'total' => $comprobante->total,
                        'otros' => $comprobante->otros,
                        'leyenda' => $comprobante->leyenda,
                        'payment' => $comprobante->payment,
                        'hash' => null,
                        'codesunat' => null,
                        'descripcionsunat' => null,
                        'moneda' => $comprobante->moneda,
                        'percent' => $comprobante->percent,
                        'status' => 0,
                        'direccion' => $comprobante->direccion,
                        'referencia' => $comprobante->seriecompleta,
                        'formapago_id' => $comprobante->formapago_id,
                        'coupon_id' => null,
                        'client_id' => $comprobante->client_id,
                        'serie_id' => $serienotacredito->id,
                        'order_id' => null,
                        'empresa_id' => $comprobante->empresa_id,
                        'user_id' => Auth::user()->id
                    ]);

                    foreach ($comprobante->comprobanteitems as $item) {
                        $notacredito->comprobanteitems()->create([
                            'item' => $item->item,
                            'descripcion' => $item->descripcion,
                            'cantidad' =>  $item->cantidad,
                            'price' => $item->price,
                            'igv' => $item->igv,
                            'unit' => $item->unit,
                            'code' => $item->code,
                            'importe' => $item->importe,
                            'pedido_id' => null,
                        ]);
                    }

                    $serienotacredito->contador = $correlativo;
                    $serienotacredito->save();
                    $comprobante->status = 2;
                    $comprobante->save();
                    DB::commit();

                    $namecert = $notacredito->empresa->cert;
                    if (Storage::disk('local')->exists('empresa/cert/' . $namecert)) {
                        if (!empty($notacredito->empresa->usuariosol) && !empty($notacredito->empresa->clavesol)) {

                            $nombreXML = $notacredito->empresa->ruc . '-' . $notacredito->serie->code . '-' . $notacredito->seriecompleta;
                            $ruta = 'xml/' . $notacredito->serie->code . '/';

                            if (!(Storage::disk('local')->exists('empresa/cert/'))) {
                                Storage::disk('local')->makeDirectory('empresa/cert/');
                            }

                            $xml = new createXML();
                            $xml->comprobanteNotaCreditoXML($ruta . $nombreXML, $notacredito->empresa, $notacredito->client, $notacredito);
                            $this->dispatchBrowserEvent('created');

                            $objApi = new SendXML();
                            $response = $objApi->enviarComprobante($notacredito->empresa, $nombreXML, storage_path('app/empresa/cert/'), storage_path('app/' . $ruta), storage_path('app/' . $ruta));
                            $mensaje = json_decode($response);

                            $notacredito->hash = $mensaje->hash;
                            $notacredito->codesunat = $mensaje->code;
                            $notacredito->descripcionsunat = $mensaje->text;
                            if ($mensaje->code == '0') {
                                $notacredito->status = 1;
                            }
                            $notacredito->save();
                            $this->emit('alert', $mensaje);
                        } else {
                            session()->flash('message', 'Configurar usuario y clave SOL de facturación.');
                        }
                    } else {
                        session()->flash('message', 'Archivo de firma digital de facturación no existe.');
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                $mensaje = ([
                    'title' => 'Serie de NOTA DE CREDITO no encontrado',
                    'text' => 'No existe serie para generar nota de crédito que anule boletas electrónicas.',
                    'type' => 'info',
                ]);
            }
        }
        // dd($mensaje);
        $this->emit('alert', ($mensaje));
    }
}
