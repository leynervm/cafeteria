<?php

namespace App\Http\Livewire\Admin\Clientes;

use App\Helpers\GetClientSunat;
use App\Models\Client;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateCliente extends Component
{
    public $open = false;
    public $document, $name, $direccion, $telefono, $dateparty;

    protected $rules = [
        'document' =>  'required',
        'name' =>  'required',
        'direccion' =>  'required',
        'telefono' =>  'required',
        'dateparty' =>  'nullable',
    ];

    public function render()
    {
        return view('livewire.admin.clientes.create-cliente');
    }

    public function save()
    {
        $this->validate();
        Client::create([
            'date' => now(),
            'document' => $this->document,
            'name' => $this->name,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'dateparty' => $this->dateparty,
            'status' => 0
        ]);
        $this->reset();
        $this->emitTo('admin.clientes.show-clientes', 'render');
        $this->emit('created');
    }

    public function searchclient()
    {

        if (Str::length(trim($this->document)) == 8 || Str::length(trim($this->document)) == 11) {
            $client = new GetClientSunat();
            $response = $client->getClient($this->document);

            if ($response->getData()) {
                $this->reset(['name', 'direccion']);
                if ($response->getData()->success) {
                    $this->name = $response->getData()->name;
                    $this->direccion = $response->getData()->direccion;
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
