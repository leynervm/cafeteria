<?php

namespace App\Http\Livewire\Admin\Mesas;

use App\Models\Location;
use App\Models\Mesa;
use App\Rules\ValidateUnique;
use Livewire\Component;

class CreateMesa extends Component
{

    public $open = false;
    public $name;
    public $location_id;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:3',
                new ValidateUnique('mesas', 'name', $this->name)
            ],
            'location_id' => ['required', 'integer', 'min:1', 'exists:locations,id']
        ];
    }

    public function render()
    {
        $locations = Location::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.mesas.create-mesa', compact('locations'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'location_id']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();
        Mesa::create([
            'name' => trim($this->name),
            'location_id' => trim($this->location_id),
            'status' => 0
        ]);
        $this->reset();
        $this->emitTo('admin.mesas.show-mesas', 'render');
        $this->emit('created');
    }
}
