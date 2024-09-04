<?php

namespace App\Http\Livewire\Admin\Agregados;

use App\Models\Agregado;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ShowAgregados extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $imagen, $identificador;
    public $agregado;
    public $open = false;

    public $search = '';

    protected $listeners = ['render', 'delete'];

    protected $rules = [
        'agregado.name' => 'required',
        'agregado.price' => 'required',
        'agregado.imagen' => 'nullable'
    ];

    public function render()
    {

        $agregados = Agregado::orderBy('name', 'asc');

        if (trim($this->search) !== '') {
            $agregados->where('name', 'ilike', '%' . $this->search . '%');
        }
        $agregados =  $agregados->paginate();
        return view('livewire.admin.agregados.show-agregados', compact('agregados'));
    }

    public function edit(Agregado $agregado)
    {
        $this->agregado = $agregado;
        $this->open = true;
    }

    public function update()
    {
        $this->validate();

        // if ($this->imagen) {
        //     $url = Storage::put($this->imagen);
        // }

        $this->agregado->save();
        $this->emit('updated');
        $this->reset();
    }

    public function delete(Agregado $agregado)
    {
        $agregado->deleteOrFail();
        $this->emit('deleted');
    }
}
