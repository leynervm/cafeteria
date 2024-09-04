<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Models\Othercosto;
use App\Rules\ValidateUnique;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateOtherCosto extends Component
{

    public $open = false;
    public $name, $price;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:3',
                new ValidateUnique('othercostos', 'name', $this->name)
            ],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.empresas.create-other-costo');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'price']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->price = trim($this->price);
        $this->validate();
        Othercosto::create([
            'name' => $this->name,
            'price' => $this->price,
            'code' => Str::random(5)
        ]);
        $this->reset();
        $this->emitTo('admin.empresas.show-other-costos', 'render');
        $this->emit('created');
    }
}
