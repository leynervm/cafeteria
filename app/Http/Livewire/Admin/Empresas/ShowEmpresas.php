<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\GetClientSunat;
use App\Models\Empresa;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ShowEmpresas extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $identificador, $idprivate, $idcert;
    public $empresa;
    public $empresa_id = 1;
    public $cert;
    public $logo = null;
    public $icono = null;
    public $publickey = null;
    public $privatekey = null;
    public $open = false;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'empresa.ruc' => ['required', 'numeric', 'digits_between:11,11'],
            'empresa.name' => ['required', 'min:3'],
            'empresa.direccion' => ['required'],
            'empresa.ubigeo' => ['required', 'digits:6'],
            'empresa.distrito' => ['required'],
            'empresa.provincia' => ['required'],
            'empresa.departamento' => ['required'],
            'empresa.zona' => ['nullable'],
            'empresa.urbanizacion' => ['required'],
            'empresa.estado' => ['required'],
            'empresa.condicion' => ['required'],
            'empresa.usuariosol' => ['nullable'],
            'empresa.clavesol' => ['nullable'],
            'empresa.moneda' => ['required', 'string'],
            'logo' => ['nullable'],
            'icono' => ['nullable'],
            'privatekey' => ['nullable'],
            'publickey' => ['nullable'],
            // 'privatekey' => ['nullable', 'mimes:pem'],
            'cert' => ['nullable'],
        ];
    }

    public function mount()
    {
        $this->identificador = rand();
        $this->idprivate = rand();
        $this->idcert = rand();
        $this->empresa = new Empresa();
        $empresa = Empresa::first();
        if ($empresa) {
            $this->empresa = $empresa;
            $this->empresa_id = $empresa->id;
        }
    }

    public function render()
    {
        return view('livewire.admin.empresas.show-empresas');
    }

    public function save()
    {

        // dd($this->logo);
        $this->validate();

        $pathlogo = $this->empresa->logo ?? null;
        $pathicono = $this->empresa->icono ?? null;

        $pathpublickey = $this->empresa->publickey ?? null;
        $pathprivatekey = $this->empresa->privatekey ?? null;

        $pathcert = $this->empresa->cert ?? null;

        DB::beginTransaction();

        try {

            if (!(Storage::exists('empresa/'))) {
                Storage::makeDirectory('empresa/');
            }

            if (!(Storage::disk('local')->exists('empresa/pem/'))) {
                Storage::disk('local')->makeDirectory('empresa/pem/');
            }

            if (!(Storage::disk('local')->exists('empresa/cert/'))) {
                Storage::disk('local')->makeDirectory('empresa/cert/');
            }

            if ($this->logo) {
                $pathlogo = $this->logo->getFilename();
                Storage::putFileAs('empresa/', $this->logo, $pathlogo);
                // $pathlogo = $this->logo->storeAs('empresa/');
            }

            if ($this->icono) {
                $pathicono = $this->icono->getFilename();
                Storage::putFileAs('empresa/', $this->icono, $pathicono);
                // $pathicono = $this->icono->store('empresa/');
            }

            if ($this->cert) {
                $pathcert = $this->empresa->ruc . '.pfx';
                if (Storage::disk('local')->exists('empresa/cert/' . $pathcert)) {
                    Storage::disk('local')->delete('empresa/cert/' . $pathcert);
                }
                $this->cert->storeAs('empresa/cert', $pathcert, 'local');
                
            }

            // if ($this->publickey) {
            //     $pathpublickey = $this->empresa->ruc . '_public.pem';
            //     if (Storage::disk('local')->exists('empresa/pem/' . $pathpublickey)) {
            //         Storage::disk('local')->delete('empresa/pem/' . $pathpublickey);
            //     }
            //     $this->publickey->storeAs('empresa/pem', $pathpublickey, 'local');
            //     // Storage::putFileAs('empresa/pem/', $this->publickey, $pathpublickey, 'local');
            //     // $this->empresa->publickey = $pathpublickey;
            // }

            // if ($this->privatekey) {
            //     $pathprivatekey = $this->empresa->ruc . '_private.pem';
            //     if (Storage::disk('local')->exists('empresa/pem/' . $pathprivatekey)) {
            //         Storage::delete('empresa/pem/' . $pathprivatekey);
            //     }
            //     $this->privatekey->storeAs('empresa/pem', $pathprivatekey, 'local');
            //     // Storage::putFileAs('empresa/pem/', $this->privatekey, $pathprivatekey, 'local');
            //     // $this->empresa->privatekey = $pathprivatekey;
            // }

            $empresa =  Empresa::updateOrCreate(
                ['id' => $this->empresa_id],
                [
                    'ruc' => $this->empresa->ruc,
                    'name' => $this->empresa->name,
                    'direccion' => $this->empresa->direccion,
                    'zona' => $this->empresa->zona,
                    'urbanizacion' => $this->empresa->urbanizacion,
                    'ubigeo' => $this->empresa->ubigeo,
                    'distrito' => $this->empresa->distrito,
                    'provincia' => $this->empresa->provincia,
                    'departamento' => $this->empresa->departamento,
                    'estado' => $this->empresa->estado,
                    'condicion' => $this->empresa->condicion,
                    'logo' => $pathlogo,
                    'icono' => $pathicono,
                    'publickey' => $pathpublickey,
                    'privatekey' => $pathprivatekey,
                    'cert' => $pathcert,
                    'usuariosol' => $this->empresa->usuariosol,
                    'clavesol' => $this->empresa->clavesol,
                    'moneda' => $this->empresa->moneda,
                    'default' => 1,
                ]
            );

            DB::commit();
            $this->resetValidation();
            $this->reset();
            $this->identificador = rand();
            $this->idprivate = rand();
            $this->cert = rand();
            $this->empresa = $empresa;
            return redirect()->route('admin.empresa');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function searchclient()
    {

        if (Str::length(trim($this->empresa->document)) == 11) {
            $client = new GetClientSunat();
            $response = $client->getClient(trim($this->empresa->document));

            if ($response->getData()) {
                $this->empresa->name = '';
                if ($response->getData()->success) {

                    $this->empresa->name = $response->getData()->name;
                    if (trim($response->getData()->direccion) !== '') {
                        $this->empresa->direccion = $response->getData()->direccion;
                    }
                    if (trim($response->getData()->estado) !== '') {
                        $this->empresa->estado = $response->getData()->estado;
                    }
                    if (trim($response->getData()->condicion) !== '') {
                        $this->empresa->condicion = $response->getData()->condicion;
                    }
                    if (trim($response->getData()->ubigeo) !== '') {
                        $this->empresa->ubigeo = $response->getData()->ubigeo;
                    }
                } else {
                    // dd($response->getData());
                    $this->resetValidation(['empresa.document']);
                    $this->addError('empresa.document', $response->getData()->mensaje);
                }
            }
        } else {
            $this->resetValidation(['empresa.document']);
            $this->addError('empresa.document', 'Dígitos del RUC no válidos.');
        }
    }
}
