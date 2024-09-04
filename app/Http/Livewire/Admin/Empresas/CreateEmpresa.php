<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CreateEmpresa extends Component
{
    use WithFileUploads;

    public $open = false;
    public $identificador;
    public $logo, $icono, $ruc, $name, $condicion, $estado,
        $direccion, $ubigeo, $distrito, $provincia, $departamento, $zona,
        $urbanizacion, $usuariosol, $clavesol;
    public $publickey, $privatekey;

    protected function rules()
    {
        return [
            'ruc' => ['required'],
            'name' => ['required'],
            'direccion' => ['required'],
            'ubigeo' => ['required'],
            'distrito' => ['required'],
            'provincia' => ['required'],
            'departamento' => ['required'],
            'zona' => ['required'],
            'urbanizacion' => ['required'],
            'estado' => ['required'],
            'condicion' => ['required'],
            'logo' => ['nullable'],
            'icono' => ['nullable'],
            'usuariosol' => ['nullable'],
            'clavesol' => ['nullable'],
            'privatekey' => ['nullable'],
            'publickey' => ['nullable'],
        ];
    }

    public function render()
    {
        $empresas = Empresa::all();
        return view('livewire.admin.empresas.create-empresa', compact('empresas'));
    }

    public function save()
    {
        $this->validate();

        if ($this->logo) {
            // Storage::put('storage', $this->logo);
        }

        if ($this->icono) {
            // Storage::put('storage', $this->icono);
        }

        if ($this->publickey) {
            // Storage::put('storage', $this->publickey);
        }

        if ($this->privatekey) {
            // Storage::put('storage', $this->privatekey);
        }

        Empresa::create([
            'ruc' => $this->ruc,
            'name' => $this->name,
            'direccion' => $this->direccion,
            'zona' => $this->zona,
            'urbanizacion' => $this->urbanizacion,
            'ubigeo' => $this->ubigeo,
            'distrito' => $this->distrito,
            'provincia' => $this->provincia,
            'departamento' => $this->departamento,
            'estado' => $this->estado,
            'condicion' => $this->condicion,
            'logo' => $this->logo,
            'icono' => $this->icono,
            'publickey' => $this->publickey,
            'privatekey' => $this->privatekey,
            'usuariosol' => $this->usuariosol,
            'clavesol' => $this->clavesol,
            'moneda' => 'PEN',
            'deleted' => 0,
        ]);

        $this->emitTo('admin.empresas.show-empresas', 'render');
        $this->identificador = rand();
        $this->reset();
    }
}
