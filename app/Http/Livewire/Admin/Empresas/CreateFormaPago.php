<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Models\Formapago;
use App\Rules\ValidateUnique;
use Livewire\Component;

class CreateFormaPago extends Component
{

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:6',
                new ValidateUnique('formapagos', 'name', $this->name)
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.empresas.create-forma-pago');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();
        Formapago::create([
            'name' => $this->name
        ]);
        $this->reset();
        $this->emitTo('admin.empresas.show-forma-pagos', 'render');
        $this->emit('created');
    }
}
