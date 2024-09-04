<?php

namespace App\Http\Livewire\Admin\Agregados;

use App\Models\Agregado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
USE Livewire\WithFileUploads;

class CreateAgregado extends Component
{

    use WithFileUploads;
    
    public $imagen, $identificador, $name, $price;
    public $open = false;

    protected $rules = [
        'name' => 'required',
        'price' => 'required',
        'imagen' => 'nullable'
    ];

    public function render()
    {
        return view('livewire.admin.agregados.create-agregado');
    }

    public function updatingOpen()
    {
        $this->identificador = rand();
        $this->reset(['name', 'price', 'imagen']);
    }

    public function save()
    {

        // if ($this->imagen) {
        //     $url = Storage::put($this->imagen);
        // }

        $this->name = trim($this->name);
        $this->validate();
        Agregado::create([
            'name' => $this->name,
            'price' => $this->price,
            'imagen' => $this->imagen,
            'unit' => 'NIU',
            'deleted' => 0,
            'user_id' => Auth::user()->id,
        ]);
        $this->emitTo('admin.agregados.show-agregados', 'render');
        $this->emit('created');
        $this->reset();
    }
}
