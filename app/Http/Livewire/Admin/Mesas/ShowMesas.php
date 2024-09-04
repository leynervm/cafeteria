<?php

namespace App\Http\Livewire\Admin\Mesas;

use App\Models\Location;
use App\Models\Mesa;
use App\Rules\ValidateUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowMesas extends Component
{

    use WithPagination;

    public $mesa;
    public $open = false;
    public $searchlocation = '';

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'mesa.name' => [
                'required', 'string', 'min:3',
                new ValidateUnique('mesas', 'name', $this->mesa->name, $this->mesa->id)
            ],
            'mesa.location_id' => ['required', 'integer', 'exists:locations,id']
        ];
    }

    public function render()
    {
        $locations = Location::orderBy('name', 'asc')->paginate();
        $locationmesas = Mesa::select('location_id')->groupBy('location_id')->with('location')->get();
        $mesas = Mesa::orderBy('name', 'asc');

        if (trim($this->searchlocation) !== '') {
            $mesas->where('location_id', $this->searchlocation);
        }

        $mesas = $mesas->paginate();

        return view('livewire.admin.mesas.show-mesas', compact('mesas', 'locations', 'locationmesas'));
    }

    public function edit(Mesa $mesa)
    {
        $this->resetValidation();
        $this->mesa = $mesa;
        $this->open = true;
    }

    public function update()
    {
        $this->mesa->name = trim($this->mesa->name);
        $this->validate();
        $this->mesa->save();
        $this->resetValidation();
        $this->emit('updated');
        $this->reset(['open']);
    }

    public function delete(Mesa $mesa)
    {
        $mesa->deleteOrFail();
        $this->emit('deleted');
    }
}
