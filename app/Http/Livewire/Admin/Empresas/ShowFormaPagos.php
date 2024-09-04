<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Models\Formapago;
use App\Rules\ValidateUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowFormaPagos extends Component
{

    use WithPagination;


    public $formapago;
    public $open = false;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'formapago.name' => [
                'required', 'min:3',
                new ValidateUnique('formapagos', 'name', $this->formapago->name, $this->formapago->id)
            ],
        ];
    }

    public function mount()
    {
        $this->formapago = new Formapago();
    }

    public function render()
    {
        $formapagos = Formapago::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.empresas.show-forma-pagos', compact('formapagos'));
    }

    public function edit(Formapago $formapago)
    {
        $this->resetValidation();
        $this->formapago = $formapago;
        $this->open = true;
    }

    public function update()
    {
        $this->formapago->name = trim($this->formapago->name);
        $this->validate();
        $this->formapago->save();
        $this->reset(['open']);
        $this->resetValidation();
        $this->emit('updated');
    }

    public function delete(Formapago $formapago)
    {
        $formapago->deleteOrFail();
        $this->emit('deleted');
    }
}
