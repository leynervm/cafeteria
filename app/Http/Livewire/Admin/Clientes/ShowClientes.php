<?php

namespace App\Http\Livewire\Admin\Clientes;

use App\Helpers\GetClientSunat;
use Illuminate\Support\Str;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ShowClientes extends Component
{

    use WithPagination;

    public $open = false;
    public $client;
    protected $listeners = ['render', 'delete'];

    protected $rules = [
        'client.document' =>  'required',
        'client.name' =>  'required',
        'client.direccion' =>  'required',
        'client.telefono' =>  'required',
        'client.dateparty' =>  'nullable',
    ];

    public function render()
    {
        $clientes = Client::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.clientes.show-clientes', compact('clientes'));
    }

    public function edit(Client $client)
    {
        $this->client = $client;
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $this->client->save();
        $this->emit('updated');
        $this->reset();
    }

    public function delete(Client $client)
    {
        $client->deleteOrFail();
        $this->emit('deleted');
    }

    public function searchclient()
    {

        if (Str::length(trim($this->client->document)) == 8 || Str::length(trim($this->client->document)) == 11) {
            $client = new GetClientSunat();
            $response = $client->getClient($this->client->document);

            if ($response->getData()) {
                $this->client->name = '';
                if ($response->getData()->success) {

                    $this->client->name = $response->getData()->name;
                    if (trim($response->getData()->direccion) !== '') {
                        $this->client->direccion = $response->getData()->direccion;
                    }
                } else {
                    // dd($response->getData());
                    $this->resetValidation(['client.document']);
                    $this->addError('client.document', $response->getData()->mensaje);
                }
            }
        } else {
            $this->resetValidation(['client.document']);
            $this->addError('client.document', 'Dígitos del documento no válidos.');
        }
    }
}
