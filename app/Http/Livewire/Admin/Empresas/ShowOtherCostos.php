<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Models\Othercosto;
use App\Rules\ValidateUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowOtherCostos extends Component
{

    use WithPagination;

    public $othercosto;
    public $open = false;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'othercosto.name' => [
                'required', 'string', 'min:3',
                new ValidateUnique('othercostos', 'name', $this->othercosto->name, $this->othercosto->id)
            ],
            'othercosto.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function mount()
    {
        $this->othercosto = new Othercosto();
    }

    public function render()
    {
        $othercostos = Othercosto::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.empresas.show-other-costos', compact('othercostos'));
    }

    public function edit(Othercosto $othercosto)
    {
        $this->resetValidation();
        $this->othercosto = $othercosto;
        $this->open = true;
    }

    public function update()
    {
        $this->othercosto->name = trim($this->othercosto->name);
        $this->validate();
        $this->othercosto->save();
        $this->resetValidation();
        $this->reset(['open']);
        $this->emit('updated');
    }

    public function delete(Othercosto $othercosto)
    {
        $othercosto->deleteOrFail();
        $this->emit('deleted');
    }
}
